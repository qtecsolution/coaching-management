<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            return redirect()->route(
                auth()->user()->user_type ? 'admin.dashboard' : 'user.dashboard'
            );
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

        return redirect(
            auth()->user()->getRedirectUrl()
        );
    }

    // function to show profile page
    public function profileView()
    {
        return view('auth.profile');
    }

    //  function to update profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . auth()->user()->id,
            'email' => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'password' => 'nullable|confirmed',
        ]);

        try {
            $user = User::find(auth()->user()->id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            alert('Success!', 'Profile updated successfully.', 'success');
            return back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }
}
