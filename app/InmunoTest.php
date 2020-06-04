<?php

namespace App;

use Patient;
use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InmunoTest extends Model
{
    use softDeletes;

    protected $fillable = [
        'register_at', 'igg_value', 'igm_value', 'patient_id', 'user_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    protected $dates = [
        'register_at'
    ];

}
