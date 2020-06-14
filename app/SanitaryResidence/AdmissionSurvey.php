<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdmissionSurvey extends Model implements Auditable //Authenticatable
{
    //
    
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        //datos de persona
        'prevision','observations',
        'contactnumber','morbid_history',
        'observations',

        //se puede aislar
        'isolate',
        

        //Criterios de Habitabilidad
        'people', 'rooms','bathrooms',


        //Criterios de Inclusión-Exclusión
        'respiratory','basicactivities','drugs','chronic','healthnow',

        //pregunta mas importante
        'residency',

        //Foraneas
        'booking_id', 'patient_id',


        'created_at'
    ];


    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    function getResultAttribute(){
        switch($this->residency) {
            case '1': return 'SI CALIFICA'; break;
            case '0': return 'NO CALIFICA'; break;            
        }
    }
    
    

}
