<?php

namespace App\SanitaryHotel;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
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
        return $this->hasMany('App\SanitaryHotel\Room');
    }
}
