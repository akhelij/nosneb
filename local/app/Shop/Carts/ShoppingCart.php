<?php

namespace App\Shop\Carts;

use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Facades\Session;
class ShoppingCart extends Cart
{
    public static $defaultCurrency;

    protected $session;

    protected $event;

    public function __construct()
    {
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        parent::__construct($this->session, $this->event);
        if(Session::has('currency'))
            self::$defaultCurrency = Session::get('currency');
        else
            self::$defaultCurrency = config('cart.currency');


    }

    public function getSession()
    {
        return app()->make('session');
    }

    public function getEvents()
    {
        return app()->make('events');
    }

    /**
     * Get the total price of the items in the cart.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @param float $shipping
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeparator = null, $shipping = 0.00,$method=null,$converted=null)
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, CartItem $cartItem) {
            
            return $total + ($cartItem->qty * $cartItem->priceTax);
            
        }, 0);

        
        $grandTotal = $total + $shipping;

        $currency_diff = 1;
    
        $currency = config('cart.currency');

        if($converted == false){
             return number_format($grandTotal, $decimals, $decimalPoint, $thousandSeparator) ;
        }else{
             if(Session::has('currency'))
            {
                $currency = Session::get('currency');
                if(Session::get('currency') == "USD" || Session::get('currency') == "EUR"){
                    $currency_diff = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=MAD_'. Session::get('currency') .'&compact=y');
                    $currency_diff = explode("}",explode(":",$currency_diff)[2])[0];
                }else{
                    Session::get('currency',"MAD");
                }
                
            }
            return number_format($grandTotal * floatval(str_replace(",",".",$currency_diff)), $decimals, $decimalPoint, $thousandSeparator) ;
           
        }
       
        
        
    }
}
