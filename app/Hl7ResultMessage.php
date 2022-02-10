<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hl7ResultMessage extends Model
{
    public function suspectCases()
    {
        return $this->hasMany('App\SuspectCase');
    }

    protected $fillable = ['full_message', 'message_id', 'patient_names', 'patient_family_father', 'patient_family_mother',
     'observation_datetime', 'observation_value', 'sample_observation_datetime', 'status', 'pdf_file', 'hl7_error_message_id'];

   public function hl7ErrorMessage() {
       return $this->belongsTo('App\Hl7ErrorMessage');
   }

    public function getObservationValueEngAttribute(){
        if ($this->observation_value == "Negativo") {
            return "negative";
        }
        if ($this->observation_value == "Positivo") {
            return "positive";
        }
        if ($this->observation_value == "Rechazado") {
            return "rejected";
        }
        if ($this->observation_value == "Indeterminado") {
            return "undetermined";
        }
    }
}
