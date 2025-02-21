<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = ['user_id', 'profile_headline','about_yourself','your_experience_id'
    ,'your_goal_id','date_of_birth','street_address','apt_suite','city','state_provience','zip_postalcode','phone_number'];


    public function yourExperience()
    {
        return $this->belongsTo(YourExperience::class, 'your_experience_id', 'id');
    }

    public function getYourGoalNameAttribute()
    {
        if(!empty($this->your_goal_id)){
            return YourGoal::find($this->your_goal_id)->name;
        }
       return;
    }

    protected $appends = ['your_goal_name'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
}
