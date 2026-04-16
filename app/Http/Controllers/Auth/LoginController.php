<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * After login check OTP verification
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->otp_verified) {
            $otp = rand(100000, 999999);

            $user->otp = $otp;
            $user->save();

            Auth::logout();

           Mail::send([], [], function ($message) use ($user, $otp) {
            $message->to($user->email)
                ->subject('OTP Verification')
                ->html("
                <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                    <div style='max-width: 500px; margin: auto; background: #ffffff; padding: 30px; border-radius: 10px; text-align: center;'>
                        
                        <h2 style='color: #333;'>OTP Verification</h2>
                        
                        <p style='color: #555; font-size: 16px;'>
                            Hello {$user->username},
                        </p>

                        <p style='color: #555; font-size: 16px;'>
                            Use the following One-Time Password (OTP) to complete your verification:
                        </p>

                        <div style='font-size: 28px; font-weight: bold; color: #2c7be5; margin: 20px 0; letter-spacing: 5px;'>
                            {$otp}
                        </div>

                        <p style='color: #999; font-size: 14px;'>
                            This OTP is valid for 5 minutes. Do not share it with anyone.
                        </p>

                        <hr style='margin: 20px 0;'>

                        <p style='color: #aaa; font-size: 12px;'>
                            If you did not request this, please ignore this email.
                        </p>

                    </div>
                </div>
                ");
        });

            session(['email' => $user->email]);

            return redirect()->route('otp.form');
        }

        // ✅ SET ONLINE
        $user->is_online = 1;
        $user->last_seen = now();
        $user->save();

        return redirect()->intended('/dashboard');
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // ✅ SET OFFLINE
            $user->is_online = 0;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}