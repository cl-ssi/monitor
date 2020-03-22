<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'run', 'dv', 'other_identification', 'name', 'fathers_family',
        'mothers_family', 'gender', 'birthday'
    ];

    protected $date = [
        'birthday'
    ];

    public function suspectCases() {
        return $this->hasMany('App\SuspectCase');
    }

    function getFullNameAttribute(){
        return $this->name . ' ' . $this->fathers_family . ' ' . $this->mothers_family;
    }

    function getGenderEspAttribute(){
        switch($this->gender) {
            case 'male': return 'Hombre'; break;
            case 'female': return 'Mujer'; break;
            case 'other': return 'Otro'; break;
            case 'unknown': return 'Desconocido'; break;
        }
    }

    function getIdentifierAttribute() {
        if(isset($this->run) and isset($this->dv)) {
            return $this->run . '-' . $this->dv;
        }
        else  {
            return $this->other_identification;
        }
    }

}
