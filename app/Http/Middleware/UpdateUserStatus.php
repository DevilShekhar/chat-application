<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserStatusChanged;

class UpdateUserStatus 
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            if (!$user->last_seen || $user->last_seen->diffInSeconds(now()) > 30) {

                if (!$user->is_online) {
                    $user->is_online = true;

                    broadcast(new UserStatusChanged($user->id, 'online'));
                }

                $user->last_seen = now();
                $user->save();
            }
        }

        return $next($request);
    }
}