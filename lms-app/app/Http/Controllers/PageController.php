<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function getViewHome()
    {
        return view('home');
    }

    public function getViewAbout()
    {
        return view('about');
    }

    public function getViewContact()
    {
        return view('contact');
    }
}
