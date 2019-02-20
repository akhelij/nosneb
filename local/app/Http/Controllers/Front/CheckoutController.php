<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Newsletter;
use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Cart\Requests\CartCheckoutRequest;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\OrderDetails\OrderProduct;
use App\Shop\OrderDetails\Repositories\OrderProductRepository;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\PaymentMethods\Exceptions\PaymentMethodNotFoundException;
use App\Shop\PaymentMethods\Payment as PaypalPayment;
use App\Shop\PaymentMethods\Paypal\Exceptions\PaypalRequestError;
use App\Shop\PaymentMethods\Paypal\PaypalExpress;
use App\Shop\PaymentMethods\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Size;
use App\Shop\Products\Transformations\ProductTransformable;
use Exception;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mail;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;
use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{
    use ProductTransformable;

    private $cartRepo;
    private $courierRepo;
    private $paymentRepo;
    private $addressRepo;
    private $customerRepo;
    private $productRepo;
    private $orderRepo;
    private $paypal;
    private $courierId;
    private $currency_diff;
    public function __construct(
        CartRepositoryInterface $cartRepository,
        CourierRepositoryInterface $courierRepository,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->courierRepo = $courierRepository;
        $this->paymentRepo = $paymentMethodRepository;
        $this->addressRepo = $addressRepository;
        $this->customerRepo = $customerRepository;
        $this->productRepo = $productRepository;
        $this->orderRepo = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->currency_diff = 1;
        $currency = config('cart.currency');
        if (Session::has('currency')) {
            $currency = Session::get('currency');
            if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
                $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
                $this->currency_diff = explode("}", explode(":", $this->currency_diff)[2])[0];
            } else {
                Session::get('currency', "MAD");
            }

        }

        $customer = $this->customerRepo->findCustomerById($this->loggedUser()->id);

        $this->courierId = request()->session()->get('courierId', 1);
        $courier = $this->courierRepo->findCourierById($this->courierId);

        $shippingCost = $this->cartRepo->getShippingFee($courier);

        $addressId = request()->session()->get('addressId', 1);
        $paymentId = request()->session()->get('paymentId', 1);

        return view('front.checkout', [
            'customer' => $customer,
            'addresses' => $customer->addresses()->get(),
            'products' => $this->cartRepo->getCartItems(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'shipping' => $shippingCost,
            'tax' => $this->cartRepo->getTax(),
            'total' => $this->cartRepo->getTotal(0, $shippingCost, null, true),
            'couriers' => $this->courierRepo->listCouriers(),
            'selectedCourier' => $this->courierId,
            'selectedAddress' => $addressId,
            'selectedPayment' => $paymentId,
            'payments' => $this->paymentRepo->listPaymentMethods(),
            'currency' => $currency,
            'currency_diff' => $this->currency_diff,
        ]);
    }

    public function getCartItems(Collection $collection)
    {
        return $collection->map(function (CartItem $item) {
            $productRepo = new ProductRepository(new Product());
            $product = $productRepo->findProductById($item->id);
            $item->product = $product;
            $item->cover = $product->cover;
            $item->description = strip_tags($product->description);
            return $item;
        });
    }

    /**
     * Execute the PayPal payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(Request $request)
    {
        try {
            $this->paypal = Session::get('apiContext');

            $apiContext = $this->paypal->getApiContext();

            $payment = Payment::get($request->input('paymentId'), $apiContext);
            $execution = $this->paypal->setPayerId($request->input('PayerID'));
            $trans = $payment->execute($execution, $apiContext);

            if (Session::has('currency') && Session::get('currency') != "MAD") {
                $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');

                $this->currency_diff = explode("}", explode(":", $this->currency_diff)[2])[0];
            }
            $diff = $this->currency_diff;
            if ($this->currency_diff == 0) {
                $diff = 1;
            }

            foreach ($trans->getTransactions() as $t) {
                $order = $this->orderRepo->create([
                    'reference' => Uuid::uuid4()->toString(),
                    'courier_id' => $request->input('courier'),
                    'customer_id' => Auth::id(),
                    'address_id' => $request->input('billing_address'),
                    'order_status_id' => 1,
                    'payment_method_id' => $request->input('payment'),
                    'discounts' => 0,
                    'total_products' => $this->cartRepo->getSubTotal(),
                    'total' => number_format($this->cartRepo->getTotal(0, 0, null, true) / $diff, 0, '.', ''),
                    'total_paid' => number_format($t->getAmount()->getTotal(0, 0, null, true) / $diff, 0, '.', ''),
                    'tax' => $this->cartRepo->getTax(),
                ]);

                $this->buildOrderDetails($order);
            }

            return redirect()->route('checkout.success');

        } catch (PayPalConnectionException $e) {
            throw new PaypalRequestError($e->getData());
        } catch (Exception $e) {
            throw new PaypalRequestError($e->getMessage());
        }
    }

    /**
     * Cancel page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel(Request $request)
    {
        return view('front.checkout-cancel', ['data' => $request->all()]);
    }

    /**
     * Success page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        $this->cartRepo->clearCart();
        if (session()->has('email') && session()->has('code_de_reduction')) {
            $newsletter = Newsletter::where("email", session()->get('email'))->where("code_de_reduction", session()->get('code_de_reduction'))->first();

            if ($newsletter != null) {

                $newsletter->code_de_reduction = null;
                $newsletter->pourcentage = 0;

                $newsletter->save();
            }
        }

        return view('front.checkout-success');
        //XWX
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCourier(Request $request)
    {
        $courier = $this->courierRepo->findCourierById($request->input('courierId'));
        $shippingCost = $this->cartRepo->getShippingFee($courier);

        request()->session()->put('courierId', $courier->id);

        return response()->json([
            'message' => 'Courier set successfully!',
            'courier' => $courier,
            'cartTotal' => $this->cartRepo->getTotal(2, $shippingCost, null, true),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAddress(Request $request)
    {
        $address = $this->addressRepo->findAddressById($request->input('addressId'));
        request()->session()->put('addressId', $address->id);

        return response()->json([
            'message' => 'Address set successfully!',
            'courier' => $address,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPayment(Request $request)
    {
        $payment = $this->paymentRepo->findPaymentMethodById($request->input('paymentId'));
        request()->session()->put('paymentId', $payment->id);

        return response()->json([
            'message' => 'Payment set successfully!',
            'payment' => $payment,
        ]);
    }

    /**
     * Build the order details
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    private function buildOrderDetails(Order $order, $payOnDelivery = false)
    {

        $products = "";

        if ($payOnDelivery) {

            $products = $this->cartRepo->getCartItems()
                ->each(function ($item) use ($order) {
                    $product = $this->productRepo->find($item->id);
                    $orderDetailRepo = new OrderProductRepository(new OrderProduct);
                    $orderDetailRepo->createOrderDetail($order, $product, $item->size, $item->qty);

                    // $this->updateStock($order, $product, $item->size, $item->qty);

                });

        } else {
            $products = $this->cartRepo->getCartItems()
                ->each(function ($item) use ($order) {
                    $product = $this->productRepo->find($item->id);
                    $orderDetailRepo = new OrderProductRepository(new OrderProduct);
                    $orderDetailRepo->createOrderDetail($order, $product, $item->size, $item->qty);
                });
        }
        $data = [
            'email' => $order->customer->email,
            'name' => $order->customer->name,
            'subject' => "Commande #" . $order->reference . " réussi !",
        ];

        $result = Mail::send('invoices.orders', ['customer' => $order->customer, 'order' => $order, 'address' => $order->address, 'products' => $products], function ($message) use ($data) {
            $message->from("contact@benson-shoes.com", "BensonShoes");
            $message->to($data['email']);
            $message->subject($data['subject']);

        });

        return $this->clearCart();
    }

    /**
     * Clear the cart
     */
    private function clearCart()
    {
        $this->cartRepo->clearCart();
        return redirect()->route('checkout.success');
    }

    public function updateStock($order, $productOrdered, $size, $quantity)
    {

        $destockage = true;
        $productType = $productOrdered->type;

        $product = Product::find($productOrdered->id);

        $productCategory = $product->categories[0]->slug;

        if ($productCategory == "accessoires" && $productOrdered->slug != "embauchoirs") {

            //$product->quantity = $product->quantity - $quantity;
            // $q_update = $bdd->prepare('UPDATE products SET  `quantity`=`quantity`-? WHERE id=?');

        } else {
            $size_label = null;
            if ($productType == "on") {
                $start = 34;
            } else {
                $start = 39;
            }
            $cmp = 1;

            for ($size_label = $start; $size_label < $start + 7; $size_label += 0.5) {

                if ($size_label == $size) {

                    break;
                } else {
                    $cmp++;
                }

            }
            $size = Size::where('product_id', $productOrdered->id)->where('size', 'size' . $cmp)->firstOrFail();
            //$product->quantity = $product->quantity - $quantity;
            $size->value = $size->value - $quantity;
            $size->save();
            //$q_update = $bdd->prepare('UPDATE products SET  `quantity`=`quantity`+? WHERE id=?');

        }

        // $product->save();

    }

    /**
     * Checkout the items
     *
     * @param CartCheckoutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws PaymentMethodNotFoundException
     * @codeCoverageIgnore
     */
    public function store(CartCheckoutRequest $request)
    {

        $discount = 0;

        if ($request->input("code") != null) {

            $existing = Newsletter::where("email", Auth::user()->email)->where("code_de_reduction", $request->input("code"))->exists();
            if ($existing) {
                $getReduction = Newsletter::where("email", Auth::user()->email)->where("code_de_reduction", $request->input("code"))->first();
                session(['email' => Auth::user()->email, 'code_de_reduction' => $request->input("code")]);

                $discount = $getReduction->pourcentage;
            } else {
                return redirect()->back()->with("error", "Code de réduction invalide");
            }

        }

        $this->currency_diff = 1;
        $currency = config('cart.currency');
        if (Session::has('currency')) {
            $currency = Session::get('currency');
            if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
                $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
                $this->currency_diff = explode("}", explode(":", $this->currency_diff)[2])[0];
            } else {
                Session::get('currency', "MAD");
            }

        }

        $method = $this->paymentRepo->findPaymentMethodById($request->input('payment'));
        // Si c'est vers le Maroc livraison gratuite
        $courierId = $request->session()->get('courierId', $request->input('courier'));
        $courier = $this->courierRepo->findCourierById($courierId);

        switch ($method->slug) {

            case 'paypal':

                $ppe = new PaypalExpress($method->client_id, $method->client_secret, $method->mode, $method->api_url);
                $payment = new PaypalPayment($ppe);
                $this->paypal = $payment->init();
                $this->paypal->setPayer();
                $this->paypal->setItems($this->getCartItems($this->cartRepo->getCartItems()));
                $this->paypal->setOtherFees(
                    $this->cartRepo->getSubTotal(),
                    $this->cartRepo->getTax(),
                    $this->cartRepo->getShippingFee($courier)
                );
                $price = $this->cartRepo->getTotal(2, $this->cartRepo->getShippingFee($courier), null, true);
                //Compter le discount
                $price -= $price * $discount / 100;

                $this->paypal->setAmount($price);
                $this->paypal->setTransactions();

                $billingAddress = $this->addressRepo->findAddressById($request->input('billing_address'));
                if ($billingAddress->country_id == "Maroc" || $billingAddress->country_id == "Morocco") {
                    $courier->cost = 0;
                }

                $this->paypal->setBillingAddress($billingAddress);

                if ($request->has('shipping_address')) {
                    $shippingAddress = $this->addressRepo->findAddressById($request->input('shipping_address'));
                }
                Session::put('apiContext', $this->paypal);
                try {
                    $response = $this->paypal->createPayment(
                        route('checkout.execute', $request->except('_token')),
                        route('checkout.cancel')
                    );

                    if ($response) {
                        $redirectUrl = $response->links[1]->href;
                        return redirect()->to($redirectUrl);
                    }
                } catch (PayPalConnectionException $e) {
                    throw new PaypalRequestError($e->getMessage());
                }
                break;

            case 'fpay':
                //$this->cartRepo->getCartItems() List exemple => "rowId":"ce7f57eb5fe686deb3a9027faac618c5","id":99,"name":"BRENDAN","qty":1,"price":1800,"options":[],"tax":0,"subtotal":1800
                //$this->cartRepo->getSubTotal() get subtotal = price wiyhout delivery
                //$this->cartRepo->getTax()
                //$this->cartRepo->getShippingFee($courier) prix de livraison
                //$this->cartRepo->getTotal(2, $this->cartRepo->getShippingFee($courier)) Amount
                //$this->addressRepo->findAddressById($request->input('billing_address')) Addresse total {"id","alias","address_1","address_2","zip","city_id","province_id","country_id","customer_id","status"}
                //$shippingAddress = $this->addressRepo->findAddressById($request->input('shipping_address')) if there is a shipping address different than billing address
                //$method {"id":2,"name","slug","description","account_id","client_id","client_secret","api_url","redirect_url","cancel_url","failed_url","mode":"sandbox"}
                //$this->customerRepo->findCustomerById(Auth::id()) {"id","name","email","status","stripe_id","card_brand","card_last_four","trial_ends_at","phone"}

                $customer = $this->customerRepo->findCustomerById(Auth::id());

                $billingAddress = $this->addressRepo->findAddressById($request->input('billing_address'));
                if ($billingAddress->country_id == "Maroc" || $billingAddress->country_id == "Morocco") {
                    $courier->cost = 0;
                }

                $AMOUNT = $this->cartRepo->getTotal(2, $this->cartRepo->getShippingFee($courier), $method->slug, false) * 100;
                $AMOUNT -= $AMOUNT * $discount / 100;

                $CURRENCY_CODE = "504";

                $TRANSACTION_CAPTURE = true;

                $MERCHANT_ID = $method->client_id;

                $MERCHANT_KEY = $method->client_secret;

                $MERCHANT_URL = "http://www.benson-shoes.com/payment/success";

                $FPAY_URLREPAUTO = "http://www.benson-shoes.com/fpayApi/response.php";

                $ORDER_ID = uniqid();

                $ORDER_DETAILS = "";

                $CUSTOMER_LASTNAME = $customer->name;
                $CUSTOMER_FIRSTNAME = $customer->name;
                $CUSTOMER_EMAIL = $customer->email;
                $CUSTOMER_PHONE = $customer->phone;
                $CUSTOMER_ADDRESS = $billingAddress->address_1 . " " . $billingAddress->address_2;
                $CUSTOMER_CITY = $billingAddress->city_id;
                $CUSTOMER_STATE = $billingAddress->province_id;
                $CUSTOMER_ZIPCODE = $billingAddress->zip;
                $CUSTOMER_COUNTRY = $billingAddress->country_id;
                $CUSTOM_DATA = "";

                $LANGUAGE = "fr";

                $FPAY_URL = $method->api_url;

                $CONVERSION_CURRENCY = "MAD";
                if (Session::get('currency') != null && Session::get('currency') != "") {
                    $CONVERSION_CURRENCY = Session::get('currency');
                }

                $AMOUNT_CONVERSION = 'false';
                if ($CONVERSION_CURRENCY != "MAD") {
                    $AMOUNT_CONVERSION = 'true';
                }

                $CONVERTED_AMOUNT = $AMOUNT;
                if ($AMOUNT_CONVERSION) {
                    $CONVERTED_AMOUNT = $AMOUNT * $this->currency_diff / 100;
                }

                $CUSTOMER_MESSAGE = "";

                $price = $this->cartRepo->getTotal(2, $this->cartRepo->getShippingFee($courier), $method->slug, false);

                $params = [
                    'reference' => $ORDER_ID,
                    'courier_id' => $courierId,
                    'customer_id' => $customer->id,
                    'address_id' => $request->input('billing_address'),
                    'order_status_id' => 5,
                    'payment_method_id' => 2,
                    'payment' => null,
                    'discounts' => $discount,
                    'total_products' => $price,
                    'total' => $price - ($price * $discount / 100), //Compter le discount
                    'tax' => 0,
                    'total_paid' => $price - ($price * $discount / 100), //Compter le discount,
                    'invoice' => null,
                ];

                $order = new Order();
                $order = $order->create($params);

                $this->buildOrderDetails($order);

                $DATA = compact("AMOUNT", "CURRENCY_CODE", "TRANSACTION_CAPTURE", "MERCHANT_ID", "MERCHANT_KEY", "MERCHANT_URL", "FPAY_URLREPAUTO", "ORDER_ID", "ORDER_DETAILS", "CUSTOMER_LASTNAME", "CUSTOMER_FIRSTNAME", "CUSTOMER_EMAIL", "CUSTOMER_PHONE", "CUSTOMER_ADDRESS", "CUSTOMER_CITY", "CUSTOMER_STATE", "CUSTOMER_ZIPCODE", "CUSTOMER_COUNTRY", "CUSTOM_DATA", "LANGUAGE", "FPAY_URL", "CONVERSION_CURRENCY", "AMOUNT_CONVERSION", "CONVERTED_AMOUNT", "CUSTOMER_MESSAGE");
                $URL = env("APP_URL");
                $BASE = storage_path() . "/logs";

                $this->sendData($BASE, $URL, $DATA);

                //sendData(storage_path(),$URL,$DATA)
                break;

            case 'paiement-a-la-livraison':
                $ORDER_ID = uniqid();
                $customer = $this->customerRepo->findCustomerById(Auth::id());
                $billingAddress = $this->addressRepo->findAddressById($request->input('billing_address'));
                if ($billingAddress->country_id == "Maroc" || $billingAddress->country_id == "Morocco") {
                    $courier->cost = 0;
                }

                $price = $this->cartRepo->getTotal(2, $this->cartRepo->getShippingFee($courier), $method->slug, false);

                $params = [
                    'reference' => $ORDER_ID,
                    'courier_id' => $courierId,
                    'customer_id' => $customer->id,
                    'address_id' => $request->input('billing_address'),
                    'order_status_id' => 6,
                    'payment_method_id' => 2,
                    'payment' => null,
                    'discounts' => $discount,
                    'total_products' => $price,
                    'total' => $price - ($price * $discount / 100), //Compter le discount,
                    'tax' => 0,
                    'total_paid' => $price - ($price * $discount / 100), //Compter le discount,
                    'invoice' => null,
                ];

                $order = new Order();
                $order = $order->create($params);

                $orderComplete = $this->buildOrderDetails($order, true);

                return redirect()->route('checkout.success');

                break;
            default:
        }
    }

    /*
     *
     *
     *
     *
     *
     *
     *               Fast Payment
     *
     *
    AMOUNT ==> Montant de la transaction dans la plus petite unité. 12,5 DH
    CURRENCY_CODE ==> Devise, 504: Dirham
    TRANSACTION_CAPTURE ==> Boolean

    ==>    Capture de la transaction.

    ==>    - true : Fpay capture automatiquement la transaction quelques minutes après son autorisation.

    ==>    - False : La capture est demandée explicitement par le marchand.

    ==>    Par défaut la capture est automatique (true)

    ==> On peut capturer un payement pour avoir le temps de validé l'existance d'une chaussure

    MERCHANT_ID ==> Donnée fournie par la société Fast Payment identifiant le compte Fpay du marchand

    MERCHANT_URL ==> URL pour rediriger le navigateur du client vers le site marchand. Ce renvoi se fait à l’initiative du client en cliquant sur le lien “Retour Marchand” de la page résultat.

    FPAY_URLREPAUTO ==> URL que FPAY fait appel pour notifier le site marchand du déroulement de la transaction.

    ORDER_ID ==> N° de la commande attribuée par le site marchand pour identifier les achats du client, cette donnée doit être unique. exemple : 123456789

    ORDER_DETAILS ==> Détail de la commande

    CUSTOMER_LASTNAME / CUSTOMER_FIRSTNAME / CUSTOMER_EMAIL / CUSTOMER_PHONE / CUSTOMER_ADDRESS / CUSTOMER_CITY
    CUSTOMER_STATE / CUSTOMER_ZIPCODE / CUSTOMER_COUNTRY / CUSTOM_DATA ==> Champ libre que le marchand peut remplir avec des données propres à lui.

    LANGUAGE ==> Langue à utiliser pour communiquer avec le client.ar, fr, en, de, …

    FPAY_URL ==> URL de FPAY, https://payment.fpayworldwide.com/sandbox/fpayreq

    AMOUNT_CONVERSION ==> Boolean, Si true, le montant converti sera affiché dans la page de paiement

    CONVERTED_AMOUNT ==> Montant converti*

    CONVERSION_CURRENCY ==> Code ISO4217 de la devise de conversion, USD, EUR

    CUSTOMER_MESSAGE ==> Message d’information à afficher à l’acheteur dans le page de paiement
     *
     *
     *
     *
    Erreur
    0 ==> OK
    -1 ==> Champs obligatoire manquant
    -2 ==> Erreur lors de l'envoie des données
    -3 ==> Erreur dans le traitement de la réponse

     *
     *
     *
     *
     *
     */

    //function to encrypt data.
    public function signData($data, $SECRETKEY)
    {
        return hash_hmac('sha256', $data, $SECRETKEY, true);
    }

    //function to add logs
    public function addLog($url, $txt)
    {
        $fileName = $url . '\fpay' . date("Y-m-d") . '.log';
        if (strpos($url, '/') !== false) {
            $fileName = $url . '/fpay' . date("Y-m-d") . '.log';
        }
        if (!file_exists($fileName)) {
            file_put_contents($fileName, "");
        }

        file_put_contents($fileName, file_get_contents($fileName) . " \r\n " . date("[j/m/y H:i:s]") . " - $txt ");
    }

    //function to send data
    public function sendData($BASE, $URL, $DATA)
    {
        $this->addLog($BASE, '<<<<==== Start sendData() ====>>>>');
        $response_code = 0;
        $FPAY_MESSAGE_VERSION = "3";
        $TRANSACTION_TYPE = 'AUTH';
        $TRANSACTION_MODE = 'MODE';

        $capture = $DATA['TRANSACTION_CAPTURE'];

        $id_marchant = $DATA['MERCHANT_ID'];
        if (!$id_marchant || empty($id_marchant)) {
            $this->addLog($BASE, 'MERCHANT_ID is null');
            return -1;
        } else {
            $MERCHANT_ID = (string) ($id_marchant);
        }

        $hmac = $DATA['MERCHANT_KEY'];
        if (!$hmac || empty($hmac)) {
            $this->addLog($BASE, 'MERCHANT_KEY is null or empty');
            return -1;
        }

        $url_fpay = $DATA['FPAY_URL'];
        if (!$url_fpay || empty($url_fpay)) {
            $this->addLog($BASE, 'FPAY_URL is null or empty');
            return -1;
        }

        $amount_convertion = $DATA['AMOUNT_CONVERSION'];
        if ($amount_convertion == 'true') {
            $converted_amount = $DATA['CONVERTED_AMOUNT'];
            if (!$converted_amount || empty($converted_amount)) {
                $this->addLog($BASE, 'CONVERTED_AMOUNT is null or empty');
                return -1;
            }

            if (strlen($converted_amount) > 12) {
                $this->addLog($BASE, 'CONVERTED_AMOUNT is too large');
                return -1;
            }

            $conversion_currency = $DATA['CONVERSION_CURRENCY'];
            if (!$conversion_currency || empty($conversion_currency)) {
                $this->addLog($BASE, 'CONVERSION_CURRENCY is null or empty');
                return -1;
            }

            if (strlen($conversion_currency > 3)) {
                $this->addLog($BASE, 'CONVERSION_CURRENCY is too large');
                return -1;
            }
        } else {
            $this->addLog($BASE, 'AMOUNT_CONVERSION is null. default value will be set to false');
            $amount_convertion = 'false';
        }

        $customer_message = $DATA['CUSTOMER_MESSAGE'];
        if (!empty($customer_message)) {
            if (strlen($customer_message) > 512) {
                $this->addLog($BASE, 'CUSTOMER_MESSAGE is large. will be tranked');
                $customer_message = substr($customer_message, 0, 512);
            }
        } else {
            $this->addLog($BASE, 'CUSTOMER_MESSAGE is not present');
        }

        $id_order = $DATA['ORDER_ID'];
        if (!$id_order || empty($id_order)) {
            $this->addLog($BASE, 'ORDER_ID is null or empty');
            return -1;
        }

        $amount = $DATA['AMOUNT'];
        if (!$amount || empty($amount)) {
            $this->addLog($BASE, 'AMOUNT is null or empty');
            return -1;
        } else {
            if (!is_int((int) $amount)) {
                $this->addLog($BASE, 'AMOUT is not int');
                return -2;
            }
        }

        $currency = $DATA['CURRENCY_CODE'];
        if (!$currency || empty($currency)) {
            $this->addLog($BASE, 'CURRENCY_CODE is null or empty');
            return -1;
        } else {
            if (!is_numeric($currency)) {
                $this->addLog($BASE, 'CURRENCY_CODE is not numeric');
                return -2;
            }
        }

        $url_marchant = $DATA['MERCHANT_URL'];
        $description = $DATA['ORDER_DETAILS'];
        $firstname = $DATA['CUSTOMER_FIRSTNAME'];
        $lastname = $DATA['CUSTOMER_LASTNAME'];
        $address = $DATA['CUSTOMER_ADDRESS'];
        $zipcode = $DATA['CUSTOMER_ZIPCODE'];
        $city = $DATA['CUSTOMER_CITY'];
        $state = $DATA['CUSTOMER_STATE'];
        $country = $DATA['CUSTOMER_COUNTRY'];
        $phone = $DATA['CUSTOMER_PHONE'];
        $lang = $DATA['LANGUAGE'];

        $email = $DATA['CUSTOMER_EMAIL'];
        if (!$email || empty($email)) {
            $this->addLog($BASE, 'CUSTOMER_EMAIL is null or empty');
            return -1;
        }

        $url_reponse = $DATA['FPAY_URLREPAUTO'];
        if (!$url_reponse || empty($url_reponse)) {
            $this->addLog($BASE, 'FPAY_URLREPAUTO is null or empty');
            return -1;
        }

        $AMOUNT = (string) ($amount);
        $AMOUNT_CONVERSION = (string) $amount_convertion;
        if (!empty($converted_amount)) {
            $CONVERTED_AMOUNT = (string) $converted_amount;
        } else {
            $CONVERTED_AMOUNT = '';
        }
        if (!empty($conversion_currency)) {
            $CONVERSION_CURRENCY = (string) $conversion_currency;
        } else {
            $CONVERSION_CURRENCY = '';
        }
        $CUSTOMER_MESSAGE = (string) $customer_message;
        $CURRENCY_CODE = $currency;
        $TRANSACTION_CAPTURE = $capture;
        $CUSTOM_DATA = 'DATA';

        if (!$id_order || empty($id_order)) {
            $this->addLog($BASE, 'Order ID is null or empty');
            return -1;
        } else {
            if (strlen($id_order) > 45) {
                $ORDER_ID = substr((string) ($id_order), 0, 45);
            } else {
                $ORDER_ID = (string) ($id_order);
            }
        }

        if (strlen($description) > 125) {
            $ORDER_DETAILS = substr((string) ($description), 0, 125);
        } else {
            $ORDER_DETAILS = (string) ($description);
        }

        $MERCHANT_URL = substr($url_marchant, 0, 255);

        if ($lastname) {
            if (strlen($lastname) > 45) {
                $CUSTOMER_LASTNAME = substr((string) ($lastname), 0, 45);
            } else {
                $CUSTOMER_LASTNAME = (string) ($lastname);
            }

        } else {
            $CUSTOMER_LASTNAME = ' ';
        }

        if ($firstname) {
            if (strlen($firstname) > 45) {
                $CUSTOMER_FIRSTNAME = substr((string) ($firstname), 0, 45);
            } else {
                $CUSTOMER_FIRSTNAME = (string) ($firstname);
            }

        } else {
            $CUSTOMER_FIRSTNAME = ' ';
        }

        if ($address) {
            if (strlen($address) > 255) {
                $CUSTOMER_ADDRESS = substr((string) ($address), 0, 255);
            } else {
                $CUSTOMER_ADDRESS = (string) ($address);
            }

        } else {
            $CUSTOMER_ADDRESS = ' ';
        }

        if ($zipcode) {
            if (strlen($zipcode) > 12) {
                $CUSTOMER_ZIPCODE = substr((string) ($zipcode), 0, 12);
            } else {
                $CUSTOMER_ZIPCODE = (string) ($zipcode);
            }

        } else {
            $CUSTOMER_ZIPCODE = ' ';
        }

        if ($city) {
            if (strlen($city) > 45) {
                $CUSTOMER_CITY = substr((string) ($city), 0, 45);
            } else {
                $CUSTOMER_CITY = (string) ($city);
            }

        } else {
            $CUSTOMER_CITY = ' ';
        }

        if ($state) {
            if (strlen($state) > 45) {
                $CUSTOMER_STATE = substr((string) ($state), 0, 45);
            } else {
                $CUSTOMER_STATE = (string) ($state);
            }

        } else {
            $CUSTOMER_STATE = ' ';
        }

        if ($country) {
            if (strlen($country) > 45) {
                $CUSTOMER_COUNTRY = substr((string) ($country), 0, 45);
            } else {
                $CUSTOMER_COUNTRY = (string) ($country);
            }

        } else {
            $CUSTOMER_COUNTRY = ' ';
        }

        /* CUSTOMER_PHONE */
        if ($phone) {
            if (strlen($phone) > 15) {
                $CUSTOMER_PHONE = substr((string) ($phone), 0, 15);
            } else {
                $CUSTOMER_PHONE = (string) ($phone);
            }

        } else {
            $CUSTOMER_PHONE = ' ';
        }

        /* CUSTOMER_EMAIL */
        if ($email) {
            if (strlen($email) > 45) {
                $CUSTOMER_EMAIL = substr((string) ($email), 0, 45);
            } else {
                $CUSTOMER_EMAIL = (string) ($email);
            }

        } else {
            $CUSTOMER_EMAIL = ' ';
        }

        /* LANGUAGE */
        if ($lang) {
            if (strlen($lang) > 2) {
                $LANGUAGE = substr((string) ($lang), 0, 2);
            } else {
                $LANGUAGE = (string) ($lang);
            }

        } else {
            $LANGUAGE = ' ';
        }

        /* FPAY_URLREPAUTO */
        if ($url_reponse) {
            if (strlen($url_reponse) > 125) {
                $FPAY_URLREPAUTO = substr((string) ($url_reponse), 0, 125);
            } else {
                $FPAY_URLREPAUTO = (string) ($url_reponse);
            }

        } else {
            $FPAY_URLREPAUTO = ' ';
        }

        $message = '';

        // converte $TRANSACTION_CAPTURE to String for MESSAGE_SIGNATURE
        $TRANSACTION_CAPTURE = ($TRANSACTION_CAPTURE) ? 'true' : 'false';
        $message = $FPAY_MESSAGE_VERSION . $MERCHANT_ID . $AMOUNT . $CURRENCY_CODE . $TRANSACTION_CAPTURE . $TRANSACTION_TYPE . $TRANSACTION_MODE . $CUSTOM_DATA . $ORDER_ID . $ORDER_DETAILS . $MERCHANT_URL . $CUSTOMER_LASTNAME . $CUSTOMER_FIRSTNAME . $CUSTOMER_ADDRESS . $CUSTOMER_ZIPCODE . $CUSTOMER_CITY . $CUSTOMER_STATE . $CUSTOMER_COUNTRY . $CUSTOMER_PHONE . $CUSTOMER_EMAIL . $LANGUAGE . $FPAY_URLREPAUTO;
        $FPAY_MERCHANT_KEY = $hmac;
        $MESSAGE_SIGNATURE = hash_hmac('sha256', $message, $FPAY_MERCHANT_KEY);
        $hexdigest = bin2hex($MESSAGE_SIGNATURE);
        $MESSAGE_SIGNATURE = strtoupper($MESSAGE_SIGNATURE);

        //Construction of JSON Object
        $this->addLog($BASE, "==> FPAY_MESSAGE_VERSION : $FPAY_MESSAGE_VERSION");
        $this->addLog($BASE, "==> MERCHANT_ID : $MERCHANT_ID");
        $this->addLog($BASE, "==> AMOUNT : $AMOUNT");
        $this->addLog($BASE, "==> CURRENCY_CODE : $CURRENCY_CODE");
        $this->addLog($BASE, "==> TRANSACTION_CAPTURE : $TRANSACTION_CAPTURE");
        $this->addLog($BASE, "==> TRANSACTION_TYPE : $TRANSACTION_TYPE");
        $this->addLog($BASE, "==> TRANSACTION_MODE : $TRANSACTION_MODE");
        $this->addLog($BASE, "==> CUSTOM_DATA : $CUSTOM_DATA");
        $this->addLog($BASE, "==> ORDER_ID : $ORDER_ID");
        $this->addLog($BASE, "==> ORDER_DETAILS : $ORDER_DETAILS");
        $this->addLog($BASE, "==> MERCHANT_URL : $MERCHANT_URL");
        $this->addLog($BASE, "==> CUSTOMER_LASTNAME : $CUSTOMER_LASTNAME");
        $this->addLog($BASE, "==> CUSTOMER_FIRSTNAME : $CUSTOMER_FIRSTNAME");
        $this->addLog($BASE, "==> CUSTOMER_ADDRESS : $CUSTOMER_ADDRESS");
        $this->addLog($BASE, "==> CUSTOMER_ZIPCODE : $CUSTOMER_ZIPCODE");
        $this->addLog($BASE, "==> CUSTOMER_CITY : $CUSTOMER_CITY");
        $this->addLog($BASE, "==> CUSTOMER_STATE : $CUSTOMER_STATE");
        $this->addLog($BASE, "==> CUSTOMER_COUNTRY : $CUSTOMER_COUNTRY");
        $this->addLog($BASE, "==> CUSTOMER_PHONE : $CUSTOMER_PHONE");
        $this->addLog($BASE, "==> CUSTOMER_EMAIL : $CUSTOMER_EMAIL");
        $this->addLog($BASE, "==> LANGUAGE : $LANGUAGE");
        $this->addLog($BASE, "==> FPAY_URLREPAUTO : $FPAY_URLREPAUTO");
        $this->addLog($BASE, "==> AMOUNT_CONVERSION : $AMOUNT_CONVERSION");
        $this->addLog($BASE, "==> CONVERTED_AMOUNT : $CONVERTED_AMOUNT");
        $this->addLog($BASE, "==> CONVERSION_CURRENCY : $CONVERSION_CURRENCY");
        $this->addLog($BASE, "==> CUSTOMER_MESSAGE : $CUSTOMER_MESSAGE");
        $this->addLog($BASE, "==> MESSAGE_SIGNATURE : $MESSAGE_SIGNATURE");

        $retour = array(
            'FPAY_MESSAGE_VERSION' => $FPAY_MESSAGE_VERSION,
            'MERCHANT_ID' => $MERCHANT_ID,
            'AMOUNT' => $AMOUNT,
            'CURRENCY_CODE' => $CURRENCY_CODE,
            'TRANSACTION_CAPTURE' => $capture,
            'TRANSACTION_TYPE' => $TRANSACTION_TYPE,
            'TRANSACTION_MODE' => $TRANSACTION_MODE,
            'CUSTOM_DATA' => $CUSTOM_DATA,
            'ORDER_ID' => $ORDER_ID,
            'ORDER_DETAILS' => $ORDER_DETAILS,
            'MERCHANT_URL' => $MERCHANT_URL,
            'AMOUNT_CONVERSION' => $AMOUNT_CONVERSION,
            'CONVERTED_AMOUNT' => $CONVERTED_AMOUNT,
            'CONVERSION_CURRENCY' => $CONVERSION_CURRENCY,
            'CUSTOMER_MESSAGE' => $CUSTOMER_MESSAGE,
            'CUSTOMER_LASTNAME' => $CUSTOMER_LASTNAME,
            'CUSTOMER_FIRSTNAME' => $CUSTOMER_FIRSTNAME,
            'CUSTOMER_ADDRESS' => $CUSTOMER_ADDRESS,
            'CUSTOMER_ZIPCODE' => $CUSTOMER_ZIPCODE,
            'CUSTOMER_CITY' => $CUSTOMER_CITY,
            'CUSTOMER_STATE' => $CUSTOMER_STATE,
            'CUSTOMER_COUNTRY' => $CUSTOMER_COUNTRY,
            'CUSTOMER_PHONE' => $CUSTOMER_PHONE,
            'CUSTOMER_EMAIL' => $CUSTOMER_EMAIL,
            'LANGUAGE' => $LANGUAGE,
            'FPAY_URLREPAUTO' => $FPAY_URLREPAUTO,
            'MESSAGE_SIGNATURE' => $MESSAGE_SIGNATURE,
        );

        // script to send JSON Data to FrontEnd.
        $content = json_encode($retour);
        $curl = curl_init($url_fpay);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // For Production Server
        //curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

        // For Test Server
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 201 && $status != 200) {
            //die("Error: call to URL $url_fpay failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            $this->addLog($BASE, '== !! erreur retournee par le front curl error : ');
            $this->addLog($BASE, "<== ERREUR : Error: call to URL $url_fpay failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            $this->addLog($BASE, '==> revoie vers page erreur : error.php ');

            return 101;
        }

        $this->addLog($BASE, "== send  JSON Data to Front ... ==>");
        curl_close($curl);
        //Response in JSON Format
        $response = json_decode($json_response, true);
        $RESPONSE_CODE = $response['RESPONSE_CODE'];
        $REASON_CODE = $response['REASON_CODE'];

        $this->addLog($BASE, "<== RESPONSE_CODE : $RESPONSE_CODE ");
        $this->addLog($BASE, "<== REASON_CODE : $REASON_CODE ");
        $REP = (int) $RESPONSE_CODE;
        // If Errors

        if ($REP != 0) {
            // Errors .
            $this->addLog($BASE, '== !! erreur retournee par le front : ');
            $this->addLog($BASE, "<== RESPONSE_CODE : $RESPONSE_CODE ");
            $this->addLog($BASE, "<== REASON_CODE : $REASON_CODE ");

            return 102;
        } else {
            $ORDER_ID = $response['ORDER_ID'];
            $FPAY_MESSAGE_VERSION = $response['FPAY_MESSAGE_VERSION'];
            $MERCHANT_ID = $response['MERCHANT_ID'];
            $RESPONSE_CODE = $response['RESPONSE_CODE'];
            $REASON_CODE = $response['REASON_CODE'];
            $REFERENCE_ID = $response['REFERENCE_ID'];
            $TRACK_ID = $response['TRACK_ID'];
            $FPAY_URL = $response['FPAY_URL'];
            $MESSAGE_SIGNATURE = $response['MESSAGE_SIGNATURE'];

            $this->addLog($BASE, "<== FPAY_MESSAGE_VERSION : $FPAY_MESSAGE_VERSION ");
            $this->addLog($BASE, "<== MERCHANT_ID : $MERCHANT_ID ");
            $this->addLog($BASE, "<== RESPONSE_CODE : $RESPONSE_CODE ");
            $this->addLog($BASE, "<== REASON_CODE : $REASON_CODE ");
            $this->addLog($BASE, "<== ORDER_ID : $ORDER_ID ");
            $this->addLog($BASE, "<== REFERENCE_ID : $REFERENCE_ID ");
            $this->addLog($BASE, "<== TRACK_ID : $TRACK_ID ");
            $this->addLog($BASE, "<== FPAY_URL : $FPAY_URL ");
            $this->addLog($BASE, "<== MESSAGE_SIGNATURE : $MESSAGE_SIGNATURE ");

            $dt = $FPAY_MESSAGE_VERSION . $MERCHANT_ID . $RESPONSE_CODE . $REASON_CODE . $ORDER_ID . $REFERENCE_ID . $TRACK_ID . $FPAY_URL;

            //send data by POST to fpay
            $home_url = $URL . 'forward_Data.php?FPAY_URL=' . $FPAY_URL . '&ORDER_ID=' . $ORDER_ID . '&REFERENCE_ID=' . $REFERENCE_ID . '&TRACK_ID=' . $TRACK_ID;
            $this->addLog($BASE, '==> send Data to URL :==> ' . $home_url);
            $this->addLog($BASE, '<<<<==== Fin sendData() ====>>>>');

            header('Location: ' . $home_url);
            exit();
        }
    }

    //function to receive data
    public function receiveData($package_Folder, $secretKey)
    {

        header('Content-type: application/json');
        header('Cache-Control: no-cache, must-revalidate');

        $this->addLog($package_Folder, " >>====  Début receiveData() =====<< ");
        $RESPONSE = "ACKNOWLEDGE=OK";
        // Verifie if receive data.
        if ($data = file_get_contents("php://input")) {
            // Read data
            $buff = file_get_contents("php://input");
            $data = json_decode($buff, true);
            $FPAY_MESSAGE_VERSION = $data['FPAY_MESSAGE_VERSION'];
            $MERCHANT_ID = $data['MERCHANT_ID'];
            $REFERENCE_ID = $data['REFERENCE_ID'];
            $TRACK_ID = $data['TRACK_ID'];
            $RESPONSE_CODE = $data['RESPONSE_CODE'];
            $REASON_CODE = $data['REASON_CODE'];
            $ORDER_ID = $data['ORDER_ID'];
            $TRANSACTION_ID = $data['TRANSACTION_ID'];
            $TRANSACTION_DATE = $data['TRANSACTION_DATE'];
            $AMOUNT = $data['AMOUNT'];
            $CURRENCY_CODE = $data['CURRENCY_CODE'];
            $TRANSACTION_STATE = $data['TRANSACTION_STATE'];
            $AMOUNT_CONVERSION = $data['AMOUNT_CONVERSION'];
            $CONVERTED_AMOUNT = $data['CONVERTED_AMOUNT'];
            $CONVERSION_CURRENCY = $data['CONVERSION_CURRENCY'];
            $CUSTOMER_MESSAGE = $data['CUSTOMER_MESSAGE'];

            $MESSAGE_SIGNATURE = $data['MESSAGE_SIGNATURE'];

            // Build the string to hache
            $dt = $FPAY_MESSAGE_VERSION . $MERCHANT_ID . $REFERENCE_ID . $TRACK_ID . $RESPONSE_CODE . $REASON_CODE . $ORDER_ID . $TRANSACTION_ID . $TRANSACTION_DATE . $AMOUNT . $CURRENCY_CODE . $TRANSACTION_STATE;
            $digest = $this->signData($dt, $secretKey);
            $hexdigest = bin2hex($digest);

            $this->addLog($package_Folder, " ====  Received Data =====");
            $this->addLog($package_Folder, "FPAY_MESSAGE_VERSION =" . $FPAY_MESSAGE_VERSION);
            $this->addLog($package_Folder, "MERCHANT_ID =" . $MERCHANT_ID);
            $this->addLog($package_Folder, "REFERENCE_ID =" . $REFERENCE_ID);
            $this->addLog($package_Folder, "RESPONSE_CODE =" . $RESPONSE_CODE);
            $this->addLog($package_Folder, "REASON_CODE =" . $REASON_CODE);
            $this->addLog($package_Folder, "ORDER_ID =" . $ORDER_ID);
            $this->addLog($package_Folder, "TRANSACTION_ID =" . $TRANSACTION_ID);
            $this->addLog($package_Folder, "TRANSACTION_DATE =" . $TRANSACTION_DATE);
            $this->addLog($package_Folder, "AMOUNT =" . $AMOUNT);
            $this->addLog($package_Folder, "CURRENCY_CODE =" . $CURRENCY_CODE);
            $this->addLog($package_Folder, "TRANSACTION_STATE =" . $TRANSACTION_STATE);
            $this->addLog($package_Folder, "signature brute =" . $dt);
            $this->addLog($package_Folder, "Received signature  =" . strtoupper($MESSAGE_SIGNATURE));
            $this->addLog($package_Folder, "Calculated signature =" . strtoupper($hexdigest));
            $AMOUNT = number_format(($AMOUNT / 100), 2, '.', '');

            //Verifying Hash
            if (strtoupper($hexdigest) == strtoupper($MESSAGE_SIGNATURE)) {
                $MERCHANT_GO = "true";
                $this->addLog($package_Folder, "MERCHANT_GO = true");
            } else {
                $MERCHANT_GO = "false";
                $RESPONSE = "SIGNATURE INVALIDE";
                $this->addLog($package_Folder, "SIGNATURE INVALIDE  == MERCHANT_GO=false ");
            }
            $retour = array(
                'FPAY_MESSAGE_VERSION' => ($FPAY_MESSAGE_VERSION),
                'MERCHANT_ID' => strtoupper($MERCHANT_ID),
                'ORDER_ID' => ($ORDER_ID),
                'REFERENCE_ID' => ($REFERENCE_ID),
                'TRACK_ID' => ($TRACK_ID),
                'MERCHANT_GO' => ($MERCHANT_GO),
                'MESSAGE_SIGNATURE' => ($MESSAGE_SIGNATURE),
            );

            $receive = array(
                'MERCHANT_ID' => $MERCHANT_ID,
                'REFERENCE_ID' => $REFERENCE_ID,
                'TRACK_ID' => $TRACK_ID,
                'RESPONSE_CODE' => $RESPONSE_CODE,
                'REASON_CODE' => $REASON_CODE,
                'ORDER_ID' => $ORDER_ID,
                'TRANSACTION_ID' => $TRANSACTION_ID,
                'TRANSACTION_DATE' => $TRANSACTION_DATE,
                'AMOUNT' => $AMOUNT,
                'CURRENCY_CODE' => $CURRENCY_CODE,
                'TRANSACTION_STATE' => $TRANSACTION_STATE,
                'MERCHANT_GO' => $MERCHANT_GO,
                'FPAY_RETURN' => json_encode($retour),
                'AMOUNT_CONVERSION' => $amount_convertion,
                'CONVERTED_AMOUNT' => $converted_amount,
                'CONVERSION_CURRENCY' => $convertion_currency,
                'CUSTOMER_MESSAGE' => $customer_message,
            );

            $this->addLog($package_Folder, " ==>  data to send : " . json_encode($retour));
            $this->addLog($package_Folder, " >>====  End receiveData() =====<<");

            return $receive;
        } else {
            $this->addLog($package_Folder, " >>==== receiveData : input = null =====<<");
            $this->addLog($package_Folder, " >>====  End receiveData() =====<<");
            return null;
        }
    }

    public function successfpay()
    {
        //$fctFpay = storage_path();
        $secretKey = "1234567890123456"; // récupéré depuis la base de données
        if (!empty($_GET["ORDER_ID"]) && !empty($_GET["REFERENCE_ID"]) && !empty($_GET["TRACK_ID"])) {
            Order::where('reference', $_GET["ORDER_ID"])->update(['track_id' => $_GET["TRACK_ID"]]);

            if ($_GET['REASON_CODE'] !== "SUCCESS") {
                return redirect()->route('checkout.cancel');
            } else {
                return redirect()->route('checkout.success');
            }

        } else {

            $data = $this->receiveData(storage_path() . '/logs/pluginfpay-test/fpayApi', $secretKey);
            if ($data != null) {
                $MERCHANT_ID = $data['MERCHANT_ID'];
                $REFERENCE_ID = $data['REFERENCE_ID'];
                $TRACK_ID = $data['TRACK_ID'];
                $RESPONSE_CODE = $data['RESPONSE_CODE'];
                $REASON_CODE = $data['REASON_CODE'];
                $ORDER_ID = $data['ORDER_ID'];
                $TRANSACTION_ID = $data['TRANSACTION_ID'];
                $TRANSACTION_DATE = $data['TRANSACTION_DATE'];
                $AMOUNT = $data['AMOUNT'];
                $CURRENCY_CODE = $data['CURRENCY_CODE'];
                $TRANSACTION_STATE = $data['TRANSACTION_STATE'];
                $MERCHANT_GO = $data['MERCHANT_GO'];
                $FPAY_RETURN = $data['FPAY_RETURN'];
                if ($MERCHANT_GO == "true") {
                    if ($RESPONSE_CODE == 0) {
                        if ($TRANSACTION_STATE == "AUTHORIZED") {
                            // MAJ avec Paiement accépté.
                        }
                        if ($TRANSACTION_STATE == "REVERSED") {
                            // MAJ avec Paiement annulé.
                        }
                        if ($TRANSACTION_STATE == "CAPTURED") {
                            // MAJ avec Paiement capturé.
                        }
                    } else {
                        if ($TRANSACTION_STATE == "EXPIRED") {
                            // MAJ Paiement abondonné
                        } else {
                            // MAJ Paiement refusé.
                        }
                    }
                }
                echo $FPAY_RETURN;
            } else {
                // si data = null
                echo
                    '{"FPAY_MESSAGE_VERSION":null,"MERCHANT_ID":"","ORDER_ID":null,"REFERENCE_ID":null,"TRACK_ID":null,"ME
		RCHANT_GO":"false","MESSAGE_SIGNATURE":null}';
            }
        }

    }
}
