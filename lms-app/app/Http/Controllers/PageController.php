<?php

namespace App\Http\Controllers;

use App\Models\HskVocabulary;

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
        $allVocabularies = HskVocabulary::where('hsk_version', '3.0')
            ->select('word', 'pinyin', 'meaning', 'meaning_en', 'level', 'topic', 'example', 'example_meaning')
            ->get();

        $vocabularies = $allVocabularies->groupBy('level');

        $topics = $allVocabularies->whereNotNull('topic')->groupBy('topic');

        return view('flashcard', compact('vocabularies', 'topics'));
    }
}
