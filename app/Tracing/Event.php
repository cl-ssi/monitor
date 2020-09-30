<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Event extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_at','event_type_id','details','symptoms','tracing_id','user_id', 'contact_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'event_at'
    ];

    public function tracing() {
        return $this->belongsTo('App\Tracing\Tracing');
    }

    public function type() {
        return $this->belongsTo('App\Tracing\EventType','event_type_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    protected $table = 'tracing_events';
}
