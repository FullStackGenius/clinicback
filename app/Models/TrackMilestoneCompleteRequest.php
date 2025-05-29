<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackMilestoneCompleteRequest extends Model
{
    protected $fillable = ['project_id', 'freelancer_id', 'milestone_id','contract_id'];
}
