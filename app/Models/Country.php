<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name','status'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class); // Define the one-to-many relationship
    }

    public function userExperiences()
    {
        return $this->hasMany(UserExperience::class);
    }
}
