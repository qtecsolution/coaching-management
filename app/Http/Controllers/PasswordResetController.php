<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ExceptionHandler;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    use ExceptionHandler;

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
        Mail::to($email)->send(new PasswordResetMail($user, $token));
    }
}
