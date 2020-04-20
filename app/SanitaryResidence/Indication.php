<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indication extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'content', 'user_id', 'booking_id', 'patient_id',
        'created_at'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function booking() {
        return $this->belongsTo('App\SanitaryResidence\Booking');
    }

    public function patient() {
        return $this->belongsTo('App\Patient');
    }
}
