<?php

namespace App;

use Patient;
use File;
use Illuminate\Database\Eloquent\Model;

class SuspectCase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sample_at', 'origin', 'age', 'gender', 'result_ifd', 'result_ifd_at',
        'subtype', 'epidemiological_week', 'epivigila', 'pscr_sars_cov_2',
        'pscr_sars_cov_2_at', 'paho_flu', 'sent_isp_at', 'status',
        'observation', 'patient_id','gestation_week','gestation'
    ];

    public function Patient() {
        return $this->belongsTo('App\Patient');
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
        }
    }

    protected $dates = [
        'sample_at', 'result_ifd_at', 'pscr_sars_cov_2_at', 'sent_isp_at'
    ];
}
