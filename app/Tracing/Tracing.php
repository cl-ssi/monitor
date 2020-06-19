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
        'notification_at','notification_mechanism',
        'next_control_at','status','discharged_at','category',
        'responsible_family_member',
        'prevision','establishment_id',
        'gestation','gestation_week',
        'symptoms','symptoms_start_at','symptoms_end_at',
        'quarantine_start_at','quarantine_end_at','cannot_quarantine',
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
        'notification_at', 'next_control_at','symptoms_start_at','symptoms_end_at','quarantine_start_at','quarantine_end_at'
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

    public function getStatusDescAttribute(){
      switch($this->status) {
          case 0: return 'SI'; break;
          case 1: return 'NO'; break;
      }

    }

    public function getRequiresLicenceDescAttribute(){
      switch($this->requires_licence) {
          case 1: return 'SI'; break;
          case 0: return 'NO'; break;
      }

    }

}
