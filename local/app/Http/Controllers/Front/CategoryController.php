<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->productRepo = $productRepository;
    }

    public function index()
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

        $category = $this->categoryRepo->findCategoryBySlug(['slug' => "collection"]);

        $repo = new CategoryRepository($category);

        return view('front.categories.category', [
            'category' => $category,
            'currency_diff' => $currency_diff,
            'currency' => $currency,
            'products' => $repo->findProducts(),
        ]);
    }
    /**
     * Find the category via the slug
     *
     * @param string $slug
     * @return \App\Shop\Categories\Category
     */
    public function getCategory(string $slug)
    {
        $category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);

        $repo = new CategoryRepository($category);
        $currency = "MAD";
        $currency_diff = 1;
        if (Session::get('currency') == "USD" || Session::get('currency') == "EUR") {
            $currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_' . Session::get('currency') . '&compact=y');
            $currency_diff = explode("}", explode(":", $currency_diff)[2])[0];
            $currency = Session::get('currency');

        } else {
            Session::get('currency', "MAD");
        }

        return view('front.categories.category', [
            'category' => $category,
            'currency_diff' => $currency_diff,
            'currency' => $currency,
            'products' => $repo->findProducts(),
        ]);
    }
}
