<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Carts\Requests\AddToCartRequest;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use ProductTransformable;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    private $courierRepo;

    /**
     * CartController constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     * @param CourierRepositoryInterface $courierRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        CourierRepositoryInterface $courierRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->courierRepo = $courierRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $currency_diff = 1;
        $currency = config('cart.currency');

        if (Session::has('currency')) {
            $currency = Session::get('currency');
            if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
                $currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
                $currency_diff = explode("}", explode(":", $currency_diff)[2])[0];
            } else {
                Session::get('currency', "MAD");
            }

        }

        $cartProducts = $this->cartRepo->getCartItems()->map(function (CartItem $item) {

            $productRepo = new ProductRepository(new Product());
            $product = $productRepo->findProductById($item->id);

            $item->product = $this->transformProduct($product);
            $item->cover = $product->cover;
            return $item;

        });

        $courier = $this->courierRepo->findCourierById(request()->session()->get('courierId', 1));
        $shippingFee = $this->cartRepo->getShippingFee($courier);

        return view('front.carts.cart', [
            'products' => $cartProducts,
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'shippingFee' => $shippingFee,
            'total' => $this->cartRepo->getTotal(2, $shippingFee, null, true),
            'currency' => $currency,
            'currency_diff' => $currency_diff,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        $product = $this->productRepo->findProductById($request->input('product'));
        $size = $request->input('size');
        $this->cartRepo->addToCart($product, $size, $request->input('quantity'));

        $request->session()->flash('message', 'Add to cart successful');
        return redirect()->route('cart.index');
    }

    /**
     * Demande a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->cartRepo->updateQuantityInCart($id, $request->input('quantity'));

        request()->session()->flash('message', 'Update cart successful');
        return redirect()->route('cart.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRepo->removeToCart($id);

        request()->session()->flash('message', 'Removed to cart successful');
        return redirect()->route('cart.index');
    }
}
