<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']); 
        // It will apply the middleware for all the function in this controller
    }

    public function index() {
        return view('dashboard');
    }
}
