<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\ProfileController;

use App\Http\Controllers\Admin\GroupsController;
use App\Http\Controllers\Admin\ChatController;

use App\Http\Controllers\Admin\OtpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;

use Illuminate\Support\Facades\Auth;



Route::get('/otp', [OtpController::class, 'otpForm'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('verify.otp');
//reset password
Route::get('/forgot-password', [ForgotPasswordOtpController::class,'showEmailForm'])->name('forgot.password');

Route::post('/send-otp', [ForgotPasswordOtpController::class,'sendOtp'])->name('send.otp');

Route::get('/verify-otp', [ForgotPasswordOtpController::class,'showOtpForm'])->name('otp.form');

Route::post('/verify-otp', [ForgotPasswordOtpController::class,'verifyOtp'])->name('verify.otp');

Route::get('/reset-password-form', [ForgotPasswordOtpController::class,'showResetForm'])->name('password.reset.form');

Route::post('/reset-password', [ForgotPasswordOtpController::class,'resetPassword'])->name('password.update.otp');
Route::get('/', function () {
    return 'Laravel Working';
});
// Dashboard (protected)
Route::get('/dashboard', [OtpController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');        // Route::get('/profile/index', [ProfileController::class, 'myProfile'])->name('profile.index');
        Route::get('/profile', [ProfileController::class, 'myProfile'])->name('profile');    
        Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        
        Route::resource('users', UsersController::class);
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [UsersController::class, 'Clientindex'])->name('index');
            Route::get('/create', [UsersController::class, 'Clientcreate'])->name('create');

            Route::post('/store', [UsersController::class, 'Clientstore'])->name('store');

            Route::get('/edit/{id}', [UsersController::class, 'Clientedit'])->name('edit'); // ✅ FIXED
            Route::put('/update/{id}', [UsersController::class, 'Clientupdate'])->name('update'); // ✅ ADDED
            Route::delete('/destroy/{id}', [UsersController::class, 'Clientdestroy'])->name('destroy'); // ✅ FIXED
        });
        Route::resource('groups', GroupsController::class);   
        // Chat UI
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

        // Group
        Route::get('/chat/group/{id}', [ChatController::class, 'groupChat']);
        Route::post('/chat/group/send', [ChatController::class, 'sendGroupMessage']);

        // Private
        Route::get('/chat/private/{id}', [ChatController::class, 'privateChat']);
        Route::post('/chat/private/send', [ChatController::class, 'sendPrivateMessage']);
        Route::post('/chat/private/send-file', [ChatController::class, 'sendFile']);
        Route::post('/chat/group/send-file', [ChatController::class, 'sendFile']);
        Route::get('/chat-file/{filename}', function ($filename) {
            $path = storage_path('app/public/chat_files/' . $filename);
            if (!file_exists($path)) {
                abort(404);
            }
            return response()->file($path);
        });
        Route::get('/chat/start/{id}', [ChatController::class, 'startChat'])->name('chat.start');
        Route::post('/chat/update-message', [ChatController::class, 'updateMessage']);
        Route::get('/chat/search-users', [ChatController::class, 'searchUsers']);
        Route::post('/chat/mark-as-read', [ChatController::class, 'markAsRead']);
        Route::post('/chat/group/mark-as-read', [ChatController::class, 'markGroupAsRead']);
});

