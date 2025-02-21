<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_id', 'message', 'seen', 'status', 'created_at', 'updated_at'
    ];
	
	public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}