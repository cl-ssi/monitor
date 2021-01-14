<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Evolution extends Model implements Auditable //Authenticatable
{
    //
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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
