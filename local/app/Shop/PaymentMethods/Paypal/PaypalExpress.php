<?php

namespace App\Shop\PaymentMethods\Paypal;

use App\Shop\Addresses\Address;
use App\Shop\Carts\ShoppingCart;
use App\Shop\PaymentMethods\Paypal\Exceptions\PaypalRequestError;
use App\Shop\Products\Product;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Collection;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use Illuminate\Support\Facades\Session;
/**
 * Class PaypalExpress
 * @package App\Shop\PaymentMethods\Paypal
 * @codeCoverageIgnore
 *
 * @todo Make a test for this
 */
class PaypalExpress
{
    private $apiContext;
    private $payer;
    private $amount;
    private $transactions = [];
    private $itemList;
    private $others;
    private $currency_diff = 1;
    private $currency = "MAD";

    public function __construct($clientId, $clientSecret, $mode, $url)
    {
        
        $apiContext = new ApiContext(
            new OAuthTokenCredential($clientId, $clientSecret)
        );
        $this->currency = config('cart.currency');
        if(Session::has('currency') && Session::get('currency')!="MAD")
        {
            $this->currency = Session::get('currency');
            if(Session::get('currency') == "USD"){
                $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_USD&compact=y');
            }elseif(Session::get('currency') == "EUR"){
                $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_EUR&compact=y');
            }
            $this->currency_diff = explode("}",explode(":",$this->currency_diff)[2])[0];
        }else {
            $this->currency = "EUR";
            $this->currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_EUR&compact=y');
            $this->currency_diff = explode("}",explode(":",$this->currency_diff)[2])[0];
        }

        $apiContext->setConfig(
            array(
                'mode' => $mode,
                'log.LogEnabled' => env('APP_DEBUG'),
                'log.FileName' => storage_path('logs/paypal.log'),
                'log.LogLevel' => env('APP_LOG_LEVEL'),
                'cache.enabled' => true,
                'cache.FileName' => storage_path('logs/paypal.cache'),
                'http.CURLOPT_SSLVERSION' => CURL_SSLVERSION_TLSv1
            )
        );

        $this->apiContext = $apiContext;
    }

    /**
     * Returns the Paypal API Context
     *
     * @return ApiContext
     */
    public function getApiContext()
    {
        
        return $this->apiContext;
    }

    public function setPayer()
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $this->payer = $payer;
    }

    /**
     * @param Collection $products
     */
    public function setItems(Collection $products)
    {
         $items = [];
        foreach ($products as $product) {
            $item = new Item();
            $item->setName($product->name)
                ->setDescription($product->description)
                ->setQuantity($product->qty)
                ->setCurrency($this->currency)
                ->setPrice(number_format($product->price * $this->currency_diff,0));
            $items[] = $item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $this->itemList = $itemList;
    }

    public function setOtherFees($subtotal, $tax = 0, $shipping)
    {
        $details = new Details();
        $details->setTax($tax)
            ->setSubtotal(number_format($subtotal * $this->currency_diff,0))
            ->setShipping(number_format($shipping * $this->currency_diff,0));

        $this->others = $details;
    }

    public function setAmount($amt)
    {
        $amount = new Amount();
        if( Session::has('currency') && Session::get('currency') == 'MAD' || !Session::has('currency')){
            $amt = $amt * $this->currency_diff;
        }

        
        $amount->setCurrency($this->currency)
            ->setTotal(number_format($amt,0))
            ->setDetails($this->others); 
        $this->amount = $amount;
    }

    public function setTransactions()
    {
        $transaction = new Transaction();
       
        $transaction->setAmount($this->amount)
            ->setItemList($this->itemList)
            ->setDescription('Payment via Paypal')
            ->setInvoiceNumber(uniqid());

        $this->transactions = $transaction;
    }

    /**
     * @param string $returnUrl
     * @param string $cancelUrl
     * @return Payment
     */
    public function createPayment(string $returnUrl, string $cancelUrl)
    {
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($this->payer)
            ->setTransactions([$this->transactions]);

        $redirectUrls = new RedirectUrls();
        $redirectUrls
            ->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);
            
        $payment->setRedirectUrls($redirectUrls);
        //die($payment);
        try {
            return $payment->create($this->apiContext);
        } catch (PayPalConnectionException $e) {
            throw new PaypalRequestError($e->getData());
        }
    }

    /**
     * @param string $payerID
     * @return PaymentExecution
     */
    public function setPayerId(string $payerID)
    {
        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);
        return $execution;
    }

    /**
     * @param Address $address
     * @return InvoiceAddress
     */
    public function setBillingAddress(Address $address)
    {
        $billingAddress = new InvoiceAddress();
        $billingAddress->line1 = $address->address_1;
        $billingAddress->line2 = $address->address_2;
        if (!is_null($address->city_id)) {
            $billingAddress->city_id = $address->city_id;
        }
        if (!is_null($address->province_id)) {
            $billingAddress->state = $address->province_id;
        }
        $billingAddress->postal_code = $address->zip;
        $billingAddress->country_code = $address->country_id;

        return $billingAddress;
    }

    /**
     * @param Address $address
     * @return ShippingAddress
     */
    public function setShippingAddress(Address $address)
    {
        $shipping = new ShippingAddress();
        $shipping->line1 = $address->address_1;
        $shipping->line2 = $address->address_2;
        if (!is_null($address->city_id)) {
            $shipping->city = $address->city_id;
        }
        if (!is_null($address->province_id)) {
            $shipping->state = $address->provinceid;
        }
        $shipping->postal_code = $address->zip;
        $shipping->country_code = $address->country_id;

        return $shipping;
    }
}
