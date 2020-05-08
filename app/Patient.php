<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends Model //Authenticatable
{
    //use Notifiable;
    use SoftDeletes;

    //protected $guard = 'patient';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'run', 'dv', 'other_identification', 'name', 'fathers_family',
        'mothers_family', 'gender', 'birthday', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];


    protected $casts = [
        'birthday'  => 'date:Y-m-d'
    ];


    public function suspectCases() {
        return $this->hasMany('App\SuspectCase');
    }

    public function lastExam() {
        return $this->hasOne('App\SuspectCase')
            ->whereIn('pscr_sars_cov_2',['positive','negative','pending'])
            ->latest();
    }

    public function getAgeAttribute() {
        if ($this->birthday)
            return Carbon::parse($this->birthday)->age;
        else return null;
    }

    public function demographic() {
        return $this->hasOne('App\Demographic');
    }

    public function bookings() {
        return $this->hasMany('App\SanitaryResidence\Booking');
    }

    public function vitalSigns() {
        return $this->hasMany('App\SanitaryResidence\VitalSign');
    }

    function getFullNameAttribute(){
        return mb_strtoupper($this->name . ' ' . $this->fathers_family . ' ' . $this->mothers_family);
    }

    function getRunExportAttribute(){
        if(isset($this->run) and isset($this->dv)) {
            return $this->run . $this->dv;
        }
        elseif($this->other_identification)  {
            return 'P'.$this->other_identification;
        }
        else {
            return '';
        }
    }

    function getSexEspAttribute(){
        switch($this->gender) {
            case 'male': return 'Masculino'; break;
            case 'female': return 'Femenino'; break;
            case 'other': return 'Otro'; break;
            case 'unknown': return 'Desconocido'; break;
        }
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

    public function logs() {
        return $this->morphMany('App\Log','model')->where('diferences','<>',"[]");
    }

}
