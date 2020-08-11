<?php

namespace App\Dialysis;

use Illuminate\Database\Eloquent\Model;

class DialysisPatient extends Model
{
    //
    protected $table = 'dialysis_patient';

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function dialysisCenter() {
        return $this->belongsTo('App\Dialysis\DialysisCenter');
    }
}
