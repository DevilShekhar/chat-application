<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'role',
        'account_status',
        'otp_verified',
        'gender',
        'profile',
        'address',
        'education',
        'bg_colors',
        'company_name',
        'founder_name',
        'company_sector',
        'company_address',
        'website_link'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_verified' => 'boolean',
            'last_seen' => 'datetime',
        ];
    }

    //  Relationships

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function otp()
    {
        return $this->hasOne(Otp::class);
    }
    public function getStatus()
    {
        // Online
        if ($this->is_online) {
            return 'online';
        }

        // Away (recently active)
        if ($this->last_seen && now()->diffInMinutes($this->last_seen) < 5) {
            return 'away';
        }

        // Offline / Logout
        return 'offline';
    }
    
}