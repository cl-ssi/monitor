<?php

namespace App;

use Patient;
use Illuminate\Database\Eloquent\Model;

class SuspectCase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sample_at', 'age', 'gender', 'result_ifd', 'epidemiological_week',
        'epivigila', 'pscr_sars_cov_2', 'paho_flu', 'observation', 'patient_id'
    ];

    public function Patient() {
        return $this->belongsTo('App\Patient');
    }

    protected $dates = [
        'sample_at'
    ];
}
