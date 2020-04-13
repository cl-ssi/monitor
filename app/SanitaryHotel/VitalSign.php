<?php

namespace App\SanitaryHotel;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'temperature','heart_rate','blood_pressure','respiratory_rate',
        'oxygen_saturation','hgt','pain_scale',
        'booking_id','patient_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function booking() {
        return $this->belongsTo('App\SanitaryHotel\Booking');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
