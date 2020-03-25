<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address','commune','telephone','telephone2','email','patient_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function logs() {
        return $this->morphMany('App\Log','model')->where('diferences','<>',"[]");
    }
}
