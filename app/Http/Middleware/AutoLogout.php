<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AutoLogout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $timeout = 300; // 5 minutes
            $lastActivity = session('lastActivityTime');

            if ($lastActivity && (time() - $lastActivity > $timeout)) {

                // ✅ Set is_online = 0 before logout
                $user = Auth::user();
                $user->is_online = 0;
                $user->save();

                Auth::logout();
                session()->flush();

                return redirect('/login')->with('message', 'Logged out due to inactivity');
            }

            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}