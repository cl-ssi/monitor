<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Tracing extends Model  implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id','index','functionary',
        'notification_mechanism','notification_at',
        'next_control_at','status','category',
        'responsible_family_member',
        'prevision','establishment_id',
        'gestation','gestation_week',
        'symptoms','symptoms_start_at','symptoms_end_at',
        'quarantine_start_at','quarantine_end_at',
        'allergies','common_use_drugs','morbid_history','chronic_diseases','family_history',
        'indications','observations',
        'help_basket','psychological_intervention','requires_hospitalization','requires_licence',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'next_control_at','symptoms_start_at','symptoms_end_at','quarantine_start_at','quarantine_end_at'
    ];

    public function events() {
        return $this->hasMany('App\Tracing\Event');
    }

    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function patient(){
        return $this->belongsTo('App\Patient');
    }


}
