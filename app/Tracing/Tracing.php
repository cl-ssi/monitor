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
        'next_control_at',
        'prevision', 'establishment_id',
        'symptoms','symptoms_start_at','symptoms_end_at',
        'quarantine_start_at','quarantine_end_at',
        'allergies','common_use_drugs','morbid_history','family_history',
        'indications','responsible_family_member','observation',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'symptoms_start_at','symptoms_end_at','quarantine_start_at','quarantine_end_at'
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
}
