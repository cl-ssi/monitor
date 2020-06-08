<?php

namespace App;

use Patient;
use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InmunoTest extends Model
{
    use softDeletes;

    protected $fillable = [
        'register_at', 'igg_value', 'igm_value', 'control','patient_id', 'user_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getIgValueAttribute() {
        switch($this->igg_value) {
          case 'positive':
            return 'positivo';
            break;
          case 'negative':
            return 'negativo';
            break;
          case 'weak':
            return 'debil';
            break;
        }
    }

    public function getImValueAttribute() {
        switch($this->igm_value) {
          case 'positive':
            return 'positivo';
            break;
          case 'negative':
            return 'negativo';
            break;
          case 'weak':
            return 'debil';
            break;
        }
    }

    public function getControlValueAttribute() {
        switch($this->control) {
          case 'yes':
            return 'si';
            break;
          case 'no':
            return 'no';
            break;
        }
    }

    protected $dates = [
        'register_at'
    ];

}
