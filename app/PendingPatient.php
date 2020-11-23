<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingPatient extends Model
{
    use SoftDeletes;

    protected $fillable = ['run', 'dv', 'other_identification', 'id', 'name', 'fathers_family', 'mothers_family', 'address',
        'region_id', 'commune_id', 'email', 'telephone', 'file_number', 'status', 'reason', 'appointment_with',
        'appointment_at', 'responsible_name', 'responsible_run', 'responsible_phone',
        'visit_observation', 'visit_delivery_at', 'visit_appointment_functionary'];

    public function commune() {
        return $this->belongsTo('App\Commune');
    }

    protected $dates = ['appointment_at', 'visit_delivery_at'];
}
