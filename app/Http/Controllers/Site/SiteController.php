<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function termsAndPolicy()
    {
        return view('site.static.terms-and-policy');
    }

    public function bannedAds()
    {
        return view('site.static.banned-ads');
    }

    public function callUs()
    {
        return view('site.static.call-us');
    }
}
