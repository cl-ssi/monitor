<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SequencingCriteria extends Model
{
    //

    protected $fillable = [
        'suspect_case_id', 'send_at',
        'critery', 'symptoms_at', 'vaccination','last_dose_at',
        'hospitalization_status', 'upc',
        'fever', 'throat_pain', 'myalgia', 'pneumonia', 'encephalitis','cough','rhinorrhea',
        'respiratory_distress', 'hypotension', 'headache', 'tachypnea', 'hypoxia', 'cyanosis', 'food_refusal', 'hemodynamic_compromise', 'respiratory_condition_deterioration', 'underlying_disease',
        'ageusia', 'anosmia',
        'type_of_vaccine', 'diarrhea', 'nasal_congestion', 'sickness',
        'fatigue', 'vomit', 'chest_pain', 'anorexy', 'asymptomatic',
        'diagnosis',
        'local_suspicion', 'result_isp'
    ];


    function getUpcEspAttribute()
    {
        if ($this->upc == 1) {
            return 'Si';
        } elseif ($this->upc == 0) {
            return 'No';
        } else {
            return '';
        }

    }

    

    public function suspectCase() {
        return $this->belongsTo('App\SuspectCase');
    }



}
