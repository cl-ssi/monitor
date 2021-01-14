<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VitalSign extends Model implements Auditable //Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'id', 'temperature','heart_rate','blood_pressure','respiratory_rate',
        'oxygen_saturation','hgt','pain_scale',
        'booking_id','patient_id', 'observations',
        'created_at'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function booking() {
        return $this->belongsTo('App\SanitaryResidence\Booking');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
