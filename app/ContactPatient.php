<?php

namespace App;

use Patient;
use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactPatient extends Model
{
    use softDeletes;

    protected $fillable = [
        'patient_id', 'patient_contact_id', 'comment', 'relationship','user_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function contact_patient() {
        return $this->belongsTo('App\Patient', 'patient_contact_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
