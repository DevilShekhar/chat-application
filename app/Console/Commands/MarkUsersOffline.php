<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Events\UserStatusChanged;

class MarkUsersOffline extends Command
{
    protected $signature = 'app:mark-users-offline';
    protected $description = 'Mark inactive users as offline';

    public function handle()
    {
        $users = User::where('last_seen', '<', now()->subMinutes(2))
            ->where('is_online', true)
            ->get();

        foreach ($users as $user) {

            $user->is_online = false;
            $user->save();

            broadcast(new UserStatusChanged($user->id, 'offline'));
        }

        return 0;
    }
}