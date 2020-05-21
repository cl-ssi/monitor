<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
