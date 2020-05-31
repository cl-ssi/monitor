<?php

namespace App\Basket;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class HelpBasket extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'run','dv','other_identification',
        'name','fathers_family','mothers_family',
        'street_type','address','number','department',
        'latitude','longitude',
        'commune_id'
    ];

    public function commune() {
        return $this->belongsTo('App\Commune');
    }

    function getFullNameAttribute(){
        return mb_strtoupper($this->name . ' ' . $this->fathers_family . ' ' . $this->mothers_family);
    }

    function getIdentifierAttribute() {
        if(isset($this->run) and isset($this->dv)) {
            return $this->run . '-' . $this->dv;
        }
        else  {
            return $this->other_identification;
        }
    }
}
