<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Traits\ExceptionHandler;

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
        if (!auth()->check()) {
            return to_route('auth.login');
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

            if ($request->has('password')) {
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
                $this->getAlert('error', 'User not found.');
                return back();
            }

            $existingToken = PasswordResetToken::where('data', $user->phone);
            if ($existingToken->exists()) {
                $existingToken->delete();
            }

            $token = PasswordResetToken::create([
                'data' => $request->data,
                'token' => Str::random(6),
            ]);

            $message = "Your password reset token is " . $token->token;

            if ($user->phone == $request->data) {
                // Send SMS
                $provider = config('SmsCredentials.active_provider');
                $config = config('SmsCredentials.providers')[$provider];

                $smsController = new SmsController();
                $smsController->sendSms($provider, $config, $user->phone, $message);

                $this->getAlert('info', 'Password reset token sent to your phone.');
            } else {
                // Send Email
                Mail::to($user->email)->send(new ForgotPasswordMail($user, $token->token));
                $this->getAlert('info', 'Password reset token sent to your email.');
            }

            return back();
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong. Try again.');

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
        try {
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

            $existingToken = PasswordResetToken::where('data', $token->data);
            if ($existingToken->exists()) {
                $existingToken->delete();
            }

            $this->getAlert('success', 'Password reset successfully.');
            return to_route('auth.login');
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withErrors(['reset' => 'Something went wrong. Try again.']);
        }
    }
}
