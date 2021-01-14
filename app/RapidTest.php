<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RapidTest extends Model implements Auditable //Authenticatable
{
    //
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    protected $fillable = [
        'register_at', 'type', 'value_test', 'patient_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }
}
