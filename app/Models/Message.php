<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_deleted',
        'file_path',
        'file_name',
        'type',
        'read_at',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected $appends = ['file_url', 'is_read_for_me', 'is_fully_read'];
    // ================= RELATIONSHIPS =================

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // ================= FILE URL =================

    public function getFileUrlAttribute()
    {
        return $this->file_path 
            ? asset('storage/' . $this->file_path) 
            : null;
    }

    // ================= 🔐 ENCRYPTION =================

    // Encrypt before saving
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    // Decrypt after retrieving
    public function getMessageAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            // fallback for old (non-encrypted) data
            return $value;
        }
    }

    // ================= SCOPES =================

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    public function scopeGroupMessages($query)
    {
        return $query->where('type', 'group');
    }

    public function scopePrivateMessages($query)
    {
        return $query->where('type', 'private');
    }
    public function getIsReadForMeAttribute()
    {
        // ✅ PRIVATE CHAT
        if ($this->type === 'private') {
            return !is_null($this->read_at);
        }

        // ✅ GROUP CHAT (IMPORTANT)
        return \DB::table('group_message_reads')
            ->where('message_id', $this->id)
            ->where('user_id', auth()->id())
            ->exists();
    }
    public function getIsFullyReadAttribute()
    {
        if ($this->type !== 'group') {
            return !is_null($this->read_at);
        }

        // ✅ FIXED TABLE NAME
        $totalMembers = \DB::table('group_members')
            ->where('group_id', $this->group_id)
            ->count();

        $readCount = \DB::table('group_message_reads')
            ->where('message_id', $this->id)
            ->count();

        return $readCount >= ($totalMembers - 1);
    }
}