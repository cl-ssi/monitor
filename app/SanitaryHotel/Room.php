<?php

namespace App\SanitaryHotel;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number','floor','description', 'hotel_id'
    ];

    public function hotel() {
        return $this->belongsTo('App\SanitaryHotel\Hotel');
    }

    public function bookings() {
        return $this->hasMany('App\SanitaryHotel\Booking');
    }
}
