<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_token',
        'user_id',
        'guest_name',
        'last_message',
        'unread_count_admin',
        'unread_count_user',
    ];

    // Karena last_message adalah data obrolan, kita enkripsi juga
    protected $casts = [
        'last_message' => 'encrypted',
    ];

    // Relasi: 1 Conversation memiliki banyak Message
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
