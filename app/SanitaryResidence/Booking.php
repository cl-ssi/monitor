<?php

namespace App\SanitaryResidence;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Http\Request;
use App\Patient;

/**
 * Booking
 *
 * @mixin Builder
 */
class Booking extends Model implements Auditable //Authenticatable
{

    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from', 'to', 'indications', 'observations', 'patient_id', 'room_id', 'prevision',
        'entry_criteria', 'prevision', 'responsible_family_member', 'relationship', 'doctor', 'diagnostic',
        'morbid_history', 'length_of_stay', 'onset_on_symptoms', 'end_of_symptoms',
        'allergies', 'commonly_used_drugs',
        'healthcare_centre', 'influenza_vaccinated', 'covid_exit_test', 'released_cause',
        'status', 'real_to'
    ];



    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['from','to','deleted_at'];


    public function getDaysAttribute() {
        if ($this->from)
            {   
                $date = Carbon::parse($this->from);
                $now = Carbon::now();
                $days = $date->diffInDays($now);
                return $days;
            }
            
        else return null;
    }


    public function patient() {
        return $this->belongsTo('App\Patient');
    }

    public function room() {
        return $this->belongsTo('App\SanitaryResidence\Room');
    }

    public function vitalSigns() {
        return $this->hasMany('App\SanitaryResidence\VitalSign');
    }

    public function indicaciones() {
        return $this->hasMany('App\SanitaryResidence\Indication');
    }

    public function evolutions() {
        return $this->hasMany('App\SanitaryResidence\Evolution')->orderBy('created_at');
    }


    public function scopeSearchRelease($query, Request $request)
    {
        if ($request->input('search') != "") {            
                $query->whereHas('patient', function ($q) use ($request) {
                $users = Patient::getPatientsBySearch($request->input('search'));
                $q->whereIn('id', $users->get('id'));
              });            
          }
    }


}
