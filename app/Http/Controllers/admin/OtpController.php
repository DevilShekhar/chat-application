<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // Show OTP page
    public function otpForm()
    {
        if (!session('email')) {
            return redirect('/login');
        }

        return view('auth.verify_otp', [
            'email' => session('email')
        ]);
    }

    // Verify OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $email = session('email');

        $user = User::where('email', $email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        // VERIFY + LOGIN STATUS
        $user->otp_verified = 1;
        $user->otp = null;
        $user->is_online = 1;
        $user->last_seen = now();
        $user->save();

        Auth::login($user);

        session()->forget('email');

        return redirect()->route('dashboard');
    }

    // Dashboard
    public function dashboard()
    {
        return view('dashboard');
    }
}