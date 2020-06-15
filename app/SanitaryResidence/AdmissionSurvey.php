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
        'water', 'work', 'food', 'risk', 'old',

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
        // switch($this->isolate) {
        //     case '1': return '<span class="bg-primary text-white">PACIENTE SE PUEDE AISLAR EN SU DOMICILIO</span>'; break;
        //     case '0': 
        //         if ($this->residency == 0) {
        //             return '<span class="bg-danger text-white">RECHAZADO PARA RESIDENCIA SANITARIA</span>'; break;
        //           }                
        //         if ($this->residency == 1) {
        //             return '<span class="bg-success text-white">APROBADO PARA RESIDENCIA SANITARIA</span>'; break;
        //           }
        //         if ($this->residency == null) {
        //             return '<span class="bg-warning text-dark">PENDIENTE VISTO BUENO</span>'; break;
        //           }
                
            
        // }
        if ($this->isolate == 1)
        {
            return '<span class="bg-primary text-white">PACIENTE SE PUEDE AISLAR EN SU DOMICILIO</span>';
            
        }
        else
        {
            if(isset($this->residency))
            {
                if ($this->isolate == 0 and $this->residency == 0)
                {
                    return '<span class="bg-danger text-white">RECHAZADO PARA RESIDENCIA SANITARIA</span>'; 
                    
                    
                }

                if ($this->isolate == 0 and $this->residency == 1)
                {
                    return '<span class="bg-success text-white">APROBADO PARA RESIDENCIA SANITARIA</span>';
                    
                    
                }
            }
            else
            {
            return '<span class="bg-warning text-dark">PENDIENTE VISTO BUENO</span>';
            }
        }
    }

    function getIsolateTextAttribute(){
        switch($this->isolate) {
                case '1': return 'SÍ'; break;
                case '0': return 'No'; break;            
        }
    
    }

}
