<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Room
 *
 * @mixin Builder
 */
class Room extends Model implements Auditable //Authenticatable
{

    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number','floor','description', 'single', 'double', 'residence_id'
    ];

    public function residence() {
        return $this->belongsTo('App\SanitaryResidence\Residence');
    }

    public function bookings() {
        return $this->hasMany('App\SanitaryResidence\Booking');
    }
}
