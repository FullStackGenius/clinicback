<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Zoha\Metable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Metable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'username',
        'user_status',
        'accept_condition',
        'apple_id',
        'google_id',
        'profile_image',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role_id',
        'country_id',
        'star_rating',
        'total_hours',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function skills()
    // {
    //     return $this->belongsToMany(Skill::class, 'user_skills');
    // }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getProfileImagePathAttribute()
    {
        if (!empty($this->profile_image)) {
            return asset('storage/user-image/' . $this->profile_image);
        }
        return asset('storage/user-image/default-user-image.jpg');
    }




    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function subCategory()
    {
        return $this->belongsToMany(SubCategory::class, 'user_subcategory')->withTimestamps();;
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skill')->withTimestamps();;
    }

    // public function getHowLikeToWork()
    // {
    //     return $this->belongsToMany(HowLikeToWork::class, 'user_like_to_works')->withTimestamps();;
    // }
    public function getHowLikeToWork()
    {
        return $this->hasOne(UserLikeToWork::class, 'user_id');
    }

    public function getRoleNameAttribute()
    {
        if (!empty($this->role_id)) {
            return Role::find($this->role_id)->name;
        }
        return;
    }
    public function getCountryNameAttribute()
    {
        if (!empty($this->country_id)) {
            return Country::find($this->country_id)->name;
        }
        return;
    }
    protected $appends = ['role_name', 'country_name', 'profile_image_path'];

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function userExperiences()
    {
        return $this->hasMany(UserExperience::class);
    }

    public function userEducation()
    {
        return $this->hasMany(UserEducation::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class,'rating_to');
    }
    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    public function userLanguage()
    {
        return $this->hasMany(UserLanguage::class);
    }

    // User Model
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'user_languages')
            ->withPivot('language_proficiency_id') // Correct field name
            ->withTimestamps();
    }


    public function contracts()
    {
        return $this->hasMany(Contract::class, 'freelancer_id', 'id');
    }
}
