<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Patient
 *
 * @mixin Builder
 */
class Patient extends Model implements Auditable //Authenticatable
{
    //use Notifiable;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    //protected $guard = 'patient';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'run', 'dv', 'other_identification', 'name', 'fathers_family',
        'mothers_family', 'gender', 'birthday', 'status', 'deceased_at'
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

    protected $dates = [ 'deceased_at'];


    public function suspectCases() {
        return $this->hasMany('App\SuspectCase');
    }

    public function inmunoTests() {
        return $this->hasMany('App\InmunoTest');
    }

    public function contactPatient() {
        return $this->hasMany('App\ContactPatient');
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

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('name','LIKE', '%'.$search.'%')
                ->orWhere('fathers_family','LIKE', '%'.$search.'%')
                ->orWhere('mothers_family','LIKE', '%'.$search.'%')
                ->orWhere('run','LIKE', '%'.$search.'%')
                ->orWhere('other_identification','LIKE', '%'.$search.'%');
        }
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

    /**
     * Retorna pacientes positivos con dirección en COMUNA
     * @return Patient[]|Builder[]|Collection
     */
    static function positivesList(){
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->with('demographic')->get();

        $communesAll = Commune::all('id');

        foreach ($communesAll as $commune){
            $communesAllArray[] = $commune->id;
        }

        $communes = array_map('trim',explode(",",env('COMUNAS')));
        $commune_not = array_diff( $communesAllArray, $communes );

        $patients = $patients->whereNotIn('demographic.commune_id', $commune_not);

        return $patients;
    }
}
