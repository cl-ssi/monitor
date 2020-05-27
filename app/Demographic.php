<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demographic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street_type','address','number','department','region_id', 'region', 'commune_id', 'commune','town',
        'telephone','latitude','longitude','telephone2','email','patient_id',
        'nationality'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function logs() {
        return $this->morphMany('App\Log','model')->where('diferences','<>',"[]");
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function commune() {
        return $this->belongsTo('App\Commune');
    }

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
