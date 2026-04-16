<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordOtpController extends Controller
{
 
    public function showEmailForm()
    {
        return view('auth.forgot-email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000,999999);

        DB::table('password_otps')->updateOrInsert(
            ['email'=>$request->email],
            [
                'otp'=>$otp,
                'expires_at'=>now()->addMinutes(10)
            ]
        );

        Mail::raw("Your OTP is: $otp", function($message) use ($request){
            $message->to($request->email)
                    ->subject('Password Reset OTP');
        });

        // ✅ FIX: store in session
        session([
            'email' => $request->email,
            'otp_sent' => true
        ]);

        return redirect()->route('otp.form');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $record = DB::table('password_otps')
                    ->where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Wrong OTP']);
        }

        // ✅ FIX: use expires_at
        if (now()->gt($record->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired']);
        }

        // delete OTP
        DB::table('password_otps')
            ->where('email', $request->email)
            ->delete();

        // store email again (important)
        session(['email' => $request->email]);

        return redirect()->route('password.reset.form');
    }
    public function showResetForm()
    {
        if (!session('email')) {
            return redirect()->route('forgot.password');
        }

        return view('auth.reset-password-otp', [
            'email' => session('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return back()->withErrors(['email'=>'User not found']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_otps')->where('email',$request->email)->delete();

        return redirect('/login')->with('success','Password reset successful');
    }
}