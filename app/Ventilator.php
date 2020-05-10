<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ventilator extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'total_real', 'no_covid'
    ];
}
