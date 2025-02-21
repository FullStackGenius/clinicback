<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = ['user_id', 'resume_url'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getResumePathAttribute()
    {
        return asset('storage/freelancer-resume/' . $this->resume_url);
    }

    protected $appended  = ['resume_path'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
