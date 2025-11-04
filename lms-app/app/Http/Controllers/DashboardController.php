<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStudentDashboard()
    {
        return view('dashboard.index');
    }
}