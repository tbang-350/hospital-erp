<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'mrn','first_name','last_name','dob','gender','phone','email','address','city','country','insurance_number','emergency_contact_name','emergency_contact_phone','preferred_language'
    ];

    protected static function booted()
    {
        static::creating(function ($patient) {
            if (empty($patient->mrn)) {
                $patient->mrn = 'MRN-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
