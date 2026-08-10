<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'message',
        'is_admin',
        'is_read',
    ];
    protected $casts = [
        'message' => 'encrypted',
        'is_admin' => 'boolean',
        'is_read' => 'boolean',
    ];
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
