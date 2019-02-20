<?php

namespace App\Http\Controllers\Front;

use App\Newsletter;
use App\Shop\Customers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use Mail;

class NewsletterController extends Controller
{
    

   

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $customerExist = Customer::where("email",$request->input('email'))->exists();
        $emailExist = Newsletter::where("email",$request->input('email'))->exists();
        if(!$emailExist || !$customerExist){
            $code_de_reduction = str_random(5);
            $newsletter= new Newsletter;
            $newsletter->email = $request->input('email');
            $newsletter->code_de_reduction = $code_de_reduction;
            $newsletter->save();
            $data = [
            'email' => $request->input('email'),
            'name' => "Nouveau client",
            'subject' => "Code de réduction",
            'code' => $code_de_reduction,
            'message' => "Voici votre code de réduction comme promis : ",
        ];

            $result = Mail::send('front.mail.code_de_reduction', ['data' => $data], function ($message) use ($data) {
                $message->from("contact@benson-shoes.com", "BensonShoes");
                $message->to($data['email']);
                $message->subject($data['subject']);
            });
            return redirect()->back()->with('message', 'Email enregistrer');
        }else{
            return redirect()->back()->with('error', 'Email déjà existant');
        }
        

        
    }

  
}
