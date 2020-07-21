<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Carbon\Carbon;

use App\Tracing\Symptom;

class Tracing extends Model  implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id','index','functionary',
        'notification_at','notification_mechanism',
        'next_control_at','status','risk_rating','discharged_at','category',
        'responsible_family_member',
        'prevision','establishment_id',
        'gestation','gestation_week',
        'symptoms','symptoms_start_at','symptoms_end_at',
        'quarantine_start_at','quarantine_end_at','cannot_quarantine',
        'allergies','common_use_drugs','morbid_history','chronic_diseases','family_history',
        'indications','observations',
        'help_basket','psychological_intervention','requires_hospitalization','requires_licence',
        'employer_name','last_day_worked','employer_contact',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'discharged_at', 'notification_at', 'next_control_at','symptoms_start_at',
        'symptoms_end_at','quarantine_start_at','quarantine_end_at'
    ];

    public function events() {
        return $this->hasMany('App\Tracing\Event');
    }

    public function tracing_requests() {
        return $this->hasMany('App\Tracing\TracingRequest');
    }

    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public function getStatusDescAttribute(){
      switch($this->status) {
          case 0: return 'SI'; break;
          case 1: return 'NO'; break;
      }

    }

    /* WTF */
    // public function getFlagRiskAttribute(){
    //     if(is_null($this->risk_rating) )
    //     {
    //         return'';
    //
    //     }
    //     else{
    //     switch($this->risk_rating) {
    //         case 0: return '<i class="fas fa-flag" style="color:#28a745;"></i>'; break;
    //         case 1: return '<i class="fas fa-flag" style="color:#ffc107;"></i>'; break;
    //         case 2: return '<i class="fas fa-flag" style="color:#dc3545;"></i>'; break;
    //         }
    //     }
    // }

    public function getFlagRiskAttribute(){
        if(is_null($this->risk_rating))
            return '';
        switch($this->risk_rating) {
            case 0: return '<i class="fas fa-flag" style="color:#28a745;"></i>'; break;
            case 1: return '<i class="fas fa-flag" style="color:#ffc107;"></i>'; break;
            case 2: return '<i class="fas fa-flag" style="color:#dc3545;"></i>'; break;
        }
    }

    public function getIndexDescAttribute(){
        switch($this->index) {
            case 0: return 'CAR'; break;
            case 1: return 'Indice'; break;
            case 2: return 'Probable'; break;
        }
    }

    public function getRequiresLicenceDescAttribute(){
      switch($this->requires_licence) {
          case 1: return 'SI'; break;
          case 0: return 'NO'; break;
      }
    }

    public function getSymptomsDescAttribute(){
      switch($this->symptoms) {
          case 1: return 'SI'; break;
          case 0: return 'NO'; break;
      }

    }

    public function getHasAcceptedLicenceAttribute(){

        if($this->tracing_requests()->exists() == false){
            return 'NO';
        }

        foreach ($this->tracing_requests as $request){
            if ($request->request_type_id == 4 || $request->request_type_id == 5){
                if($request->request_complete_at && $request->rejection == null){
                    return 'SI';
                }
            }
            return 'NO';
        }

    }

    public function getSymptoms(){
        //obtiene sintomas
        $symptoms = "";
        foreach ($this->events as $key => $event) {
            $symptoms = $symptoms . $event->symptoms . ",";
        }
        //elimina ultimo caracter
        $symptoms = substr($symptoms, 0, -1);

        //se genera array resultado
        $resultado = explode(",", $symptoms);
        $resultado = array_unique(explode(",", $symptoms));
        $resultado = array_map('trim', $resultado);
        //crea array
        $symptoms = Symptom::all();
        $array_final = array();
        foreach ($symptoms as $key => $symptom) {
            if ($symptom->name == "Anosmia" || $symptom->name == "Ageusia" ||
                $symptom->name == "Dolor toráxico" || $symptom->name == "Calofrios" ||
                $symptom->name == "Otro") {
            }else{
                $array_final[$symptom->name] = false;
            }
        }
        //se obtiene info
        $array_otros = array();
        foreach ($resultado as $key => $result) {
            if ($result == "Anosmia" || $result == "Ageusia" || $result == "Dolor toráxico" || $result == "Calofrios" || $result == "Otro") {
                array_push($array_otros, $result);
            }else{
                // array_push($array_final, $result);
                $array_final[$result] = true;
            }
        }
        array_push($array_final, $array_otros);
        return $array_final;
    }

    // fiebre, tos, dificultad respiratoria, dolor muscular, dolor de garganta, dolor de cabeza, diarrea, otro
    //
    // "1"	"Fiebre"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "2"	"Tos"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "11"	"Dificultad para respirar"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "3"	"Mialgias"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "4"	"Odinofagia"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "10"	"Cefalea"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "8"	"Diarrea"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    //
    // "5"	"Anosmia"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "6"	"Ageusia"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "7"	"Dolor toráxico"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "9"	"Calofrios"	"2020-06-17 15:19:52"	"2020-06-17 15:19:52"	\N
    // "12"	"Otro"	"2020-06-18 23:27:07"	"2020-06-18 23:27:08"	\N


    protected static function booted()
    {
        /* this is executed after ->save() method */
        // static::created(function ($tracing) {
        //     $birthday = Carbon::parse($tracing->patient->birthday);
        //     $year_birthday = $birthday->year;
        //     $query = "NUEVO_CASO";
        //
        //     if($tracing->patient->demographic){
        //       $geo_address = $tracing->patient->demographic->latitude.';'.$tracing->patient->demographic->longitude;
        //     }
        //     else{
        //       $geo_address = '';
        //     }
        //
        //     $data = [
        //         'id'=> $tracing->patient->id.'-'.$year_birthday,
        //         'direccion'=> $geo_address,
        //         'comuna'=> $tracing->patient->demographic->commune->id,
        //         'fecha_inicio'=> $tracing->quarantine_start_at->format('Y-m-d'),
        //         'fecha_termino'=> $tracing->quarantine_end_at->format('Y-m-d'),
        //         'app' => env('APP_WS_UNAP'),
        //         'key' => env('KEY_WS_UNAP'),
        //         'query' => 'NUEVO_CASO'
        //     ];
        //
        //     $client = new \GuzzleHttp\Client();
        //
        //     $response = $client->request('GET', env('WS_UNAP'), [
        //         'query' => $data
        //     ]);
        //
        //     $responseJson = $response->getBody()->getContents();
        // });

        /* this is executed after ->save() method */
        // static::updated(function ($tracing) {
        //
        // });
    }

}
