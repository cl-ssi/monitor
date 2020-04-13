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
        'name', 'address','telephone'
    ];

    public function rooms() {
        return $this->hasMany('App\SanitaryResidence\Room');
    }
}
