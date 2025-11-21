<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ExceptionHandler;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ExceptionHandler;

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
            if (auth()->user()->status) {
                return redirect()->route(
                    auth()->user()->user_type ? 'admin.dashboard' : 'user.dashboard'
                );
            }

            auth()->logout();
            return back()->withErrors(['login' => 'Your account is suspended.']);
        }

        return back()->withErrors(['login' => 'Invalid credentials']);
    }

    // function to logout user
    public function logout()
    {
        auth()->logout();
        return redirect()->route('auth.login');
    }

    // function to show profile page
    public function profileView()
    {
        if (!auth()->check()) {
            return to_route('auth.login');
        }

        return view('auth.profile');
    }

    //  function to update profile
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return to_route('auth.login');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . Auth::user()->id,
            'email' => 'nullable|email|unique:users,email,' . Auth::user()->id,
            'password' => 'nullable|confirmed',
        ]);

        try {
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;

            if ($request->has('password')) {
                if (isDemoAccount($user->phone)) {
                    $this->getAlert('error', 'You cannot change password for demo account.');
                    return back();
                }
                $user->password = bcrypt($request->password);
            }

            $user->save();

            $this->getAlert('success', 'Profile updated successfully.');
            return back();
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong. Try again.');

            return back();
        }
    }

    // function to update avatar
    public function updateAvatar(Request $request)
    {
        if (!auth()->check()) {
            return to_route('auth.login');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $user = User::find(auth()->id());

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    fileRemove($user->avatar);
                }

                $file = $request->file('avatar');
                $avatar = fileUpload($file, 'avatars');

                $user->update([
                    'avatar' => $avatar
                ]);
            }

            $this->getAlert('success', 'Avatar updated successfully.');
            return back();
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong. Try again.');

            return back();
        }
    }
}
