<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapidTest extends Model
{
    //
    use softDeletes;

    protected $fillable = [
        'register_at', 'type', 'value_test', 'patient_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }
}
