<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
/**
 * Residence
 *
 * @mixin Builder
 */
class Residence extends Model implements Auditable //Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name', 'address','telephone',
        'width','height'
    ];

    public function rooms() {
        return $this->hasMany('App\SanitaryResidence\Room');
    }

    /**
    * The user that belong to the residence.
    */
    public function users() {
        return $this->belongsToMany('App\User');
    }
}
