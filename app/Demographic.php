<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Demographic
 *
 * @mixin Builder
 */
class Demographic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street_type','address','number','department','suburb',
        'region_id','commune_id','city','nationality',
        'latitude','longitude',
        'telephone','telephone2','email','patient_id'
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


    function getFullAddressAttribute(){
        return mb_strtoupper($this->address . ' ' . $this->number . ' ' . $this->department);
    }

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
