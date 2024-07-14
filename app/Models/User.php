<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function projectCreatedBy()
    {
        return $this->hasMany(Project::class, 'created_by', 'id');
    }

    public function projectUpdatedBy()
    {
        return $this->hasMany(Project::class, 'updated_by', 'id');
    }

    public function taskAssigneddTo()
    {
        return $this->hasMany(Task::class, 'assigned_user_id', 'id');
    }

    public function taskCreatedBy()
    {
        return $this->hasMany(Task::class, 'created_by', 'id');
    }
    public function taskUpdatedBy()
    {
        return $this->hasMany(Task::class, 'updated_by', 'id');
    }
}
