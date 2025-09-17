<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'date_of_birth', 'classroom_id'];

    //relationships
    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}