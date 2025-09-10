<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'department', 'position', 
        'phone', 'email', 'hire_date', 'salary', 'qualifications'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'qualifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
