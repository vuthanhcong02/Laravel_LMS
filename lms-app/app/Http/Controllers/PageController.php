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

    public function getVIewRoadMap()
    {
        return view('roadmap');
    }

    public function getViewCourses()
    {
        return view('course');
    }

    public function getViewBlog()
    {
        return view('blog');
    }

    public function getViewFlashcards()
    {
        return view('flashcard');
    }
}
