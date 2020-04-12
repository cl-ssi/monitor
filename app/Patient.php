<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'mothers_family', 'gender', 'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','birthday'];


    public function suspectCases() {
        return $this->hasMany('App\SuspectCase');
    }

    public function demographic() {
        return $this->hasOne('App\Demographic');
    }

    public function bookings() {
        return $this->hasMany('App\SanitaryHotel\Booking');
    }

    public function vitalSigns() {
        return $this->hasMany('App\SanitaryHotel\VitalSign');
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

    public function logs() {
        return $this->morphMany('App\Log','model')->where('diferences','<>',"[]");
    }

}
