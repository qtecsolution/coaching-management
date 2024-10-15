<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // function to show login page
    public function loginView()
    {
        return view('auth.login');
    }

    // function to login user
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $remember = $request->has('remember_me') ? true : false;

        if (auth()->attempt(['phone' => $request->phone, 'password' => $request->password], $remember)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid credentials']);
    }

    // function to logout user
    public function logout()
    {
        auth()->logout();
        return redirect()->route('auth.login');
    }

    // function to show signup page
    public function signupView()
    {
        return view('auth.signup');
    }

    // function to signup user
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        auth()->login($user);

        return redirect()->route('admin.dashboard');
    }
}
