<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionSurvey extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'people', 'rooms', 'residency','observations',
        'booking_id', 'patient_id',
        'created_at'
    ];


    public function patient() {
        return $this->belongsTo('App\Patient');
    }
    

}
