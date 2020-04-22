<?php

namespace App;

use Patient;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuspectCase extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'age', 'gender', 'sample_at', 'epidemiological_week',
        'origin', 'status', 'result_ifd_at', 'result_ifd', 'subtype',
        'pscr_sars_cov_2_at', 'pscr_sars_cov_2', 'sample_type', 'validator_id',
        'sent_isp_at', 'external_laboratory', 'paho_flu', 'epivigila',
        'gestation', 'gestation_week', 'close_contact',
        'notification_at', 'notification_mechanism',
        'discharged_at',
        'observation',
        'patient_id', 'laboratory_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function validator() {
        return $this->belongsTo('App\User','validator_id');
    }

    public function laboratory() {
        return $this->belongsTo('App\Laboratory');
    }

    public function logs() {
        return $this->morphMany('App\Log','model');
    }

    public function files() {
        return $this->hasMany('App\File');
    }

    function getCovid19Attribute(){
        switch($this->pscr_sars_cov_2) {
            case 'pending': return 'Pendiente'; break;
            case 'positive': return 'Positivo'; break;
            case 'negative': return 'Negativo'; break;
            case 'undetermined': return 'Indeterminado'; break;
        }
    }

    function getSentExternalAtAttribute() {
        return ($this->sent_isp_at)?
            $this->sent_isp_at->format('d-m-Y'):'';
    }

    function getProcesingLabAttribute() {
        if($this->external_laboratory) {
            return $this->external_laboratory;
        }
        switch($this->laboratory_id) {
            case 1: return 'HETG'; break;
            case 2: return 'UNAP'; break;
        }
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'sample_at', 'result_ifd_at', 'pscr_sars_cov_2_at', 'sent_isp_at',
        'notification_at', 'discharged_at', 'deleted_at'
    ];

}
