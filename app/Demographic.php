<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Demographic
 *
 * @mixin Builder
 */
class Demographic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street_type','address','number','department','suburb',
        'region_id','commune_id','city','nationality',
        'latitude','longitude',
        'telephone','telephone2','email','workplace','patient_id'
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

    function getFullTelephonesAttribute(){
        return mb_strtoupper($this->telephone . ' ' . $this->telephone2);
    }

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
