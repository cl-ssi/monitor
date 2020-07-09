<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Carbon\Carbon;

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
        'next_control_at','status','discharged_at','category',
        'responsible_family_member',
        'prevision','establishment_id',
        'gestation','gestation_week',
        'symptoms','symptoms_start_at','symptoms_end_at',
        'quarantine_start_at','quarantine_end_at','cannot_quarantine',
        'allergies','common_use_drugs','morbid_history','chronic_diseases','family_history',
        'indications','observations',
        'help_basket','psychological_intervention','requires_hospitalization','requires_licence',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'notification_at', 'next_control_at','symptoms_start_at','symptoms_end_at','quarantine_start_at','quarantine_end_at'
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
