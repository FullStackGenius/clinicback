<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable =  ['chat_id', 'message','sender_id','is_read','file_path','file_type'];
    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];

    public function getFullFilePathAttribute()
    {
        if (!empty($this->file_path)) {
            return asset('storage/messages/' . $this->file_path);
        }
       return;
    }
    protected $appends = ['full_file_path'];

   

    public function user()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
}
