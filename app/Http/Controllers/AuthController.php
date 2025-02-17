<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ExceptionHandler;
use App\Traits\MailConfig;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ExceptionHandler, MailConfig;

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

    // function to show forgot password page
    public function forgotPasswordView()
    {
        return view('auth.forgot-pass');
    }

    // function to send password reset token
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!User::where('phone', $value)->orWhere('email', $value)->exists()) {
                        $fail('The ' . $attribute . ' does not exist.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::where('phone', $request->data)->orWhere('email', $request->data)->firstOrFail();

            // Delete old token if exists
            PasswordResetToken::where('data', $user->phone)
                ->orWhere('data', $user->email)
                ->delete();

            // Generate new token
            $token = PasswordResetToken::create([
                'data' => $request->data,
                'token' => Str::random(6),
            ]);

            $message = "Your password reset token is " . $token->token;

            if ($user->phone == $request->data) {
                $this->sendSmsToken($user->phone, $message);
                $this->getAlert('info', 'Password reset token sent to your phone.');
            } else {
                $this->sendEmailToken($user->email, $user, $token->token);
                $this->getAlert('info', 'Password reset token sent to your email.');
            }

            return back();
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withErrors(['error' => 'Something went wrong. Try again.']);
        }
    }

    // function to show reset password page
    public function resetPasswordView($token)
    {
        $token = PasswordResetToken::where('token', $token)->firstOrFail();
        return view('auth.reset-pass', compact('token'));
    }

    // function to reset password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'token' => 'required|exists:password_reset_tokens,token',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $token = PasswordResetToken::where('token', $request->token)->firstOrFail();
            $user = User::where('phone', $token->data)->orWhere('email', $token->data)->firstOrFail();

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Remove used token
            PasswordResetToken::where('data', $token->data)->delete();

            $this->getAlert('success', 'Password reset successfully.');
            return to_route('auth.login');
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withErrors(['reset' => 'Something went wrong. Try again.']);
        }
    }

    /**
     * Sends password reset token via SMS.
     */
    private function sendSmsToken($phone, $message)
    {
        $provider = config('SmsCredentials.active_provider');
        $config = config('SmsCredentials.providers')[$provider];

        $smsController = new SmsController();
        $smsController->sendSms($provider, $config, $phone, $message);
    }

    /**
     * Sends password reset token via Email.
     */
    private function sendEmailToken($email, $user, $token)
    {
        $mailConfig = $this->mailConfig();
        $mailConfig->to($email)->send(new ForgotPasswordMail($user, $token));
    }
}
