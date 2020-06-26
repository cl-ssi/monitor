<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TracingRequest extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_at','request_type_id','details', 'validity_at', 'tracing_id','user_id','request_complete_at',
        'rejection','request_complete_details','user_complete_request_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'request_at','request_complete_at'
    ];

    public function tracing() {
        return $this->belongsTo('App\Tracing\Tracing');
    }

    public function type() {
        return $this->belongsTo('App\Tracing\RequestType','request_type_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function request_complete_user() {
        return $this->belongsTo('App\User', 'user_complete_request_id');
    }

    protected $table = 'tracing_requests';
}
