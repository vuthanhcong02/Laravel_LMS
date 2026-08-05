<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HskMockExamController extends Controller
{
    /**
     * Display HSK Mock Exams Menu
     */
    public function index()
    {
        return view('portal.student.hsk-mock-exams.index');
    }

    public function show($level)
    {
        return view('portal.student.hsk-mock-exams.show', compact('level'));
    }

    public function take($level, $id)
    {
        return view('portal.student.hsk-mock-exams.take', compact('level', 'id'));
    }
}
