<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Make sure you have resources/views/admin/dashboard.blade.php
        return view('admin.dashboard');
    }
}