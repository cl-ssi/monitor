<?php

namespace App\Lab\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Covid19 extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier','name','fathers_family','mothers_family',
        'origin','sample_at',
        'reception_at', 'result_at', 'result',
        'user_id','recpetor_id','validator_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['sample_at','reception_at','result_at'];

}
