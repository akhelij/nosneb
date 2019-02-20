<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
/**
 * Admin routes
 */

Route::namespace ('Admin')->group(function () {
    Route::get('admin04081994/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('admin04081994/login', 'LoginController@login')->name('admin.login');
    Route::get('admin04081994/logout', 'LoginController@logout')->name('admin.logout');
});
Route::group(['prefix' => 'admin04081994', 'middleware' => ['admin'], 'as' => 'admin.'], function () {
    Route::namespace ('Admin')->group(function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::group(['middleware' => ['role:admin,guard:admin']], function () {
            Route::namespace ('Products')->group(function () {
                Route::resource('products', 'ProductController');
                //Route::post('products', 'ProductController@reset')->name('product.reset');
                Route::get('remove-image-product', 'ProductController@removeImage')->name('product.remove.image');
                Route::get('remove-image-thumb', 'ProductController@removeThumbnail')->name('product.remove.thumb');
            });
            Route::namespace ('Customers')->group(function () {
                Route::resource('customers', 'CustomerController');
                Route::resource('customers.addresses', 'CustomerAddressController');
            });
            Route::namespace ('Categories')->group(function () {
                Route::resource('categories', 'CategoryController');
                Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
            });
            Route::namespace ('Orders')->group(function () {
                Route::resource('orders', 'OrderController');
                Route::resource('order-statuses', 'OrderStatusController');
                Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
            });
            Route::resource('employees', 'EmployeeController');
            Route::get('employees/{id}/profile', 'EmployeeController@getProfile')->name('employee.profile');
            Route::put('employees/{id}/profile', 'EmployeeController@updateProfile')->name('employee.profile.update');
            Route::resource('addresses', 'Addresses\AddressController');
            Route::resource('countries', 'Countries\CountryController');
            Route::resource('countries.provinces', 'Provinces\ProvinceController');
            Route::resource('countries.provinces.cities', 'Cities\CityController');
            Route::resource('couriers', 'Couriers\CourierController');
            Route::resource('payment-methods', 'PaymentMethods\PaymentMethodController');
            Route::resource('attributes', 'Attributes\AttributeController');
            Route::resource('attributes.values', 'Attributes\AttributeValueController');
        });
    });
});

/**
 * Frontend routes
 */
Auth::routes(['verify' => true]);
Route::namespace ('Auth')->group(function () {
    Route::get('cart/login', 'CartLoginController@showLoginForm')->name('cart.login');
    Route::post('cart/login', 'CartLoginController@login')->name('cart.login');
    Route::get('logout', 'LoginController@logout');

    Route::get('/register/confirm/{token}', 'RegisterController@verify');

});

Route::namespace ('Front')->group(function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/changeCurrency', 'HomeController@currency')->name('currency');
    Route::get('/about', 'AboutController@index')->name('about');
    Route::get('/invoices/order', 'AboutController@index')->name('order');
    Route::get('/shop', 'ShopController@index')->name('shop');
    Route::get('/maintain', 'AboutController@maintain')->name('maintain');
    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::post('/contact', 'ContactController@mail');
    Route::post('/newsletter/store', 'NewsletterController@store')->name("store_newsletter");

    Route::get('terms-of-use', 'HomeController@terms')->name('terms');
    Route::group(['middleware' => ['auth']], function () {
        Route::get('accounts', 'AccountsController@index')->name('accounts');
        Route::get('checkout', 'CheckoutController@index')->name('checkout.index');
        Route::post('checkout', 'CheckoutController@store')->name('checkout.store');
        Route::post('set-courier', 'CheckoutController@setCourier')->name('set.courier');
        Route::post('set-address', 'CheckoutController@setAddress')->name('set.address');
        Route::get('checkout/execute', 'CheckoutController@execute')->name('checkout.execute');
        Route::get('checkout/cancel', 'CheckoutController@cancel')->name('checkout.cancel');
        Route::get('checkout/success', 'CheckoutController@success')->name('checkout.success');
        Route::get('payment/success', 'CheckoutController@successfpay')->name('payment.success');
        Route::post('deleteAddress', 'CustomerAddressController@deleteAddress')->name('deleteAddress');

        Route::resource('customer.address', 'CustomerAddressController');
    });
    Route::resource('cart', 'CartController');
    Route::get("category/{slug}", 'CategoryController@getCategory')->name('front.category.slug');
    Route::get("category", 'CategoryController@index')->name('catalog');
    Route::get("search", 'ProductController@search')->name('search.product');
    Route::get("{product}", 'ProductController@show')->name('front.get.product');
    Route::post("demande", "ProductController@demande")->name('product.demande');

});
