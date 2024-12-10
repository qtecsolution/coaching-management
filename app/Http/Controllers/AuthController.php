<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        return abort(403, 'Unauthorized action.');

        return view('auth.signup');
    }

    // function to signup user
    public function signup(Request $request)
    {
        return abort(403, 'Unauthorized action.');

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
        if (!auth()->check()) {
            return redirect()->route('auth.login');
        }

        return view('auth.profile');
    }

    //  function to update profile
    public function updateProfile(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login');
        }

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

    public function updateAvatar(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login');
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

            alert('Success!', 'Avatar updated successfully.', 'success');
            return back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    public function forgotPasswordView()
    {
        return view('auth.forgot-pass');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'data' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!User::where('phone', $value)->orWhere('email', $value)->exists()) {
                        $fail('The ' . $attribute . ' does not exist.');
                    }
                },
            ],
        ]);

        try {
            $user = User::where('phone', $request->data)
                ->orWhere('email', $request->data)
                ->first();

            if (!$user) {
                alert('Oops!', 'User not found.', 'error');
                return back();
            }

            // Ensure only one token exists at a time
            PasswordResetToken::where('data', $user->phone)->delete();

            $token = PasswordResetToken::create([
                'data' => $user->phone,
                'token' => Str::random(6),
            ]);

            $message = "Your password reset token is " . $token->token;

            if ($user->phone == $request->data) {
                // Send SMS
                $provider = config('smsCredentials.active_provider');
                $config = config('smsCredentials.providers')[$provider];

                $smsController = new SmsController();
                $smsController->sendSms($provider, $config, $user->phone, $message);

                alert('Success!', 'Password reset token sent to your phone.', 'success');
            } else {
                // Send Email
                Mail::to($user->email)->send(new ForgotPasswordMail($user, $token->token));
                alert('Success!', 'Password reset token sent to your email.', 'success');
            }

            return back();
        } catch (\Throwable $th) {
            Log::error("Error: {$th->getMessage()} at line {$th->getLine()} in {$th->getFile()}");

            alert('Oops!', 'Something went wrong. Please try again later.', 'error');
            return back();
        }
    }

    public function resetPasswordView($token)
    {
        $token = PasswordResetToken::where('token', $token)->firstOrFail();
        return view('auth.reset-pass', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $token = PasswordResetToken::where('token', $request->token)->firstOrFail();
        $user = User::where('phone', $token->data)
            ->orWhere('email', $token->data)
            ->firstOrFail();

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        PasswordResetToken::where('data', $user->phone)->delete();

        alert('Success!', 'Password reset successfully.', 'success');
        return redirect()->route('auth.login');
    }
}
