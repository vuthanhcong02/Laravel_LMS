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

    public function getViewCourses()
    {
        return view('course.index');
    }

    public function getViewCourseDetail($id)
    {
        return view('course.show', ['id' => $id]);
    }
}
