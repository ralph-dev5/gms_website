<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create'); // Create this Blade file
    }

    public function index()
    {
        return view('reports.index'); // Create this Blade file
    }
}