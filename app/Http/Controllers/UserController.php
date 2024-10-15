<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // function to show users
    public function index()
    {
        $users = User::where('is_admin', true)->get();
        return view('admin.user.index', compact('users'));
    }
}
