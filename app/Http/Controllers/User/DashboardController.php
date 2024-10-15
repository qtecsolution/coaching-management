<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        return view('user.dashboard');
    }
}
