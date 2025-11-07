<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';
    protected $fillable = ['doctors_id', 'patient_id', 'date', 'time'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctors_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
