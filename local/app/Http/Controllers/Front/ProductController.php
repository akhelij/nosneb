<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $list = $this->productRepo->searchProduct(request()->input('q'));

        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        $currency = "MAD";
        $currency_diff = 1;
        if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
            $currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
            $currency_diff = explode("}", explode(":", $currency_diff)[2])[0];
            $currency = Session::get('currency');
        } else {
            Session::get('currency', "MAD");
        }

        $products = $this->productRepo->paginateArrayResults($products, 10);
        return view('front.products.product-search', compact('products', 'currency_diff', 'currency'));
    }

    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $currency = "MAD";
        $currency_diff = 1;
        if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
            $currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
            $currency_diff = explode("}", explode(":", $currency_diff)[2])[0];
            $currency = Session::get('currency');
        } else {
            Session::get('currency', "MAD");
        }

        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $product_linked = Product::where('sku', $product->sku)->get();
        $images = $product->images()->get();
        $productAttributes = $product->attributes()->get();
        return view('front.products.product', compact('product', 'product_linked', 'images', 'productAttributes', 'currency_diff', 'currency'));
    }

    public function demande(Request $request)
    {
        //$product = $this->productRepo->findProductById($request->input('product'));
        //$size = $request->input('size');

        return redirect()->back()->with('demande', 'Merci');
    }
}
