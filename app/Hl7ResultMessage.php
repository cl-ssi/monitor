<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hl7ResultMessage extends Model
{
    protected $fillable = ['full_message', 'message_id', 'patient_names', 'patient_family_father', 'patient_family_mother',
     'observation_datetime', 'observation_value', 'sample_observation_datetime', 'status'];
}
