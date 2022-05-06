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
        'register_at', 'type', 'value_test', 'patient_id', 'epivigila', 'observation'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    function getValueEspAttribute(){
        switch($this->value_test) {
            case 'Positive': return 'Positivo'; break;
            case 'Negative': return 'Negativo'; break;
            case 'Weak Positive': return 'Positivo Débil'; break;
        }
    }

    protected $dates = ['register_at'];


}
