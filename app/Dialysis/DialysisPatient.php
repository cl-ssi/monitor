<?php

namespace App\Dialysis;

use Illuminate\Database\Eloquent\Model;

class DialysisPatient extends Model
{
    protected $fillable = [
        'id','patient_id','establishment_id'
    ];
    //
    protected $table = 'dialysis_patient';

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }
    
}
