<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_id', 'project_id', 'rating', 'feedback', 'created_at', 'updated_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}