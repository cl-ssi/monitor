<?php

namespace App\Dialysis;

use Illuminate\Database\Eloquent\Model;

class DialysisCenter extends Model
{
    //
    protected $fillable = [
        'id','name','commune_id'
    ];


    public function commune() {
        return $this->belongsTo('App\Commune');
    }
}
