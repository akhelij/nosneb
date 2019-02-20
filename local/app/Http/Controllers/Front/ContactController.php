<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use Mail;

class ContactController extends Controller
{

    public function index()
    {
        return view('layouts.front.contact');
    }

    public function mail(ContactFormRequest $request)
    {
        $data = [
            'email' => $request->email,
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        $result = Mail::send('front.mail.mail', ['data' => $data], function ($message) use ($data) {
            $message->from("contact@benson-shoes.com", $data['name']);
            $message->to("contact@benson-shoes.com");
            $message->subject("Email : " . $data['email'] . " --- " . $data['subject']);
        });
        // redirect the user back

        return view('layouts.front.contact', ['message' => 'Votre message a été envoyé']);

    }
}
