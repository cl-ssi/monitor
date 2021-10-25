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
        'observations', 'symptoms_epivigila',

        //se puede aislar
        'isolate',
        

        //Criterios de Habitabilidad
        'people', 'rooms','bathrooms',


        //Criterios de Inclusión-Exclusión
        'respiratory','basicactivities','drugs','chronic','healthnow',
        'water', 'work', 'food', 'risk', 'old',

        //pregunta mas importante
        'residency',

        //Foraneas
        'booking_id', 'patient_id',


        'created_at'
    ];


    public function patient() {
        return $this->belongsTo('App\Patient')->withTrashed();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    function getResultAttribute(){
        if ($this->isolate == 1 and $this->residency == 1)
        {
                 return '<span class="text-success">SE PUEDE AISLAR</span> <span class="text-danger"><br> CALIFICADOR APRUEBA R.S. </span>';
        }

        if ($this->isolate == 1 and $this->residency == 0)
        {
                 return '<span class="text-success">SE PUEDE AISLAR<br>  CALIFICADOR RECHAZA R.S. </span>';
        }

        if ($this->isolate == 0 and $this->residency == 1)
        {
            return '<span class="text-danger">NO SE PUEDE AISLAR <br> CALIFICADOR APRUEBA R.S. </span>';
        }

        if ($this->isolate == 0 and $this->residency == 0)
        {
            return '<span class="text-danger">NO SE PUEDE AISLAR </span> <span class="text-success"><br>CALIFICADOR RECHAZA R.S. </span></></span>';
        }
        
    }

    function getIsolateTextAttribute(){
        switch($this->isolate) {
                case '1': return 'SÍ'; break;
                case '0': return 'No'; break;            
        }
    
    }

    function getResidencyTextAttribute(){
        switch($this->residency) {
                case '1': return 'SÍ'; break;
                case '0': return 'No'; break;            
        }
    
    }

}
