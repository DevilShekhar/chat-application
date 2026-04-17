<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// ✅ PRIVATE CHAT
Broadcast::channel('chat.{id}', function ($user, $id) {
    return true; // allow all (you can restrict later)
});

// ✅ GROUP CHAT
Broadcast::channel('group.{id}', function ($user, $id) {
    return true;
});