<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        if (! Newsletter::hasMember($request->email))
            Newsletter::subscribe($request->email);

        return view('app.newsletter-subscribed');
    }
}
