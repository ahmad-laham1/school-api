<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'role' => UserRole::class,
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user that will be stored in the subject claim of the JWT
    }

    public function getJWTCustomClaims(): array
    {
        return []; // Return a key-value array containing any custom claims to be added to the JWT
    }

    //relationships
    public function classrooms(){
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function student(){
        return $this->hasOne(Student::class);
    }


    //helper methods to check user roles
    public function isAdmin(){
        return $this->role === UserRole::Admin;
    }


    public function isTeacher(){
        return $this->role === UserRole::Teacher;
    }

    public function isStudent(){
        return $this->role == UserRole::Student;
    }

}
