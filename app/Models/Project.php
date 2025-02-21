<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{


    protected $fillable =  ['title', 'description', 'project_status', 'budget_type', 'hourly_from', 'hourly_to', 'fixed_rate', 'project_type_id',
     'user_id', 'project_scope_id', 'project_duration_id', 'project_experience_id','next_step','completed_steps'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
   

    public function getProjectStatusLabelAttribute()
    {
        $statuses = [
            1 => 'pending',
            2 => 'draft',
            3 => 'publish',
            4 => 'closed',
        ];

        return $statuses[$this->project_status] ?? 'Unknown'; // Default to 'Unknown' if no match
    }
    
    public function getBudgetTypeLabelAttribute()
    {
        $statuses = [
            1 => 'hourly',
            2 => 'fixed ',
        ];

        return $statuses[$this->budget_type] ?? 'Unknown'; // Default to 'Unknown' if no match
    }

    public function getProjectTypeLabelAttribute()
    {
        if (!empty($this->project_type_id )) {
            return ProjectType::find($this->project_type_id )->name;
        }
        return;
    }
    protected $appends = ['project_status_label','budget_type_label','project_type_label'];


    public function projectSkill(){
        return $this->belongsToMany(Skill::class,'project_skills')->withTimestamps();;
    }
    public function projectScope(){
        return $this->belongsTo(ProjectScope::class);
    }
    public function projectDuration(){
        return $this->belongsTo(ProjectDuration::class);
    }
    public function projectExperience(){
        return $this->belongsTo(ProjectExperience::class);
    }


    public function projectCategory(){
        return $this->belongsToMany(Category::class,'project_categories')->withTimestamps();;
    }

    public function projectSubCategory(){
        return $this->belongsToMany(SubCategory::class,'project_sub_categories')->withTimestamps();;
    }

    public function clientUser(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function contracts(){
        return $this->hasOne(Contract::class,'project_id','id');
    }
    
}
