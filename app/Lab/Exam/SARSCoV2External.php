<?php

namespace App\Lab\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SARSCoV2External extends Model
{
    protected $table = 'sars_cov_2_external';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'run','dv','other_identification',
        'name','fathers_family','mothers_family',
        'gender', 'birthday',
        'telephone','email','address','commune_id',
        'establishment_id', 'sample_type', 'sample_at',
        'reception_at', 'result_at', 'result',
        'user_id','receptor_id','validator_id',
        'run_medic', 'run_responsible'
    ];

    function getIdentifierAttribute() {
        if(isset($this->run) and isset($this->dv)) {
            return $this->run . '-' . $this->dv;
        }
        else  {
            return 'E:'.$this->other_identification;
        }
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

    function getFullNameAttribute(){
        return mb_strtoupper($this->name . ' ' . $this->fathers_family . ' ' . $this->mothers_family);
    }

    public function getAgeAttribute() {
        if ($this->birthday)
            return Carbon::parse($this->birthday)->age;
        else return null;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('id',$search)
                ->orWhere('name','LIKE', '%'.$search.'%')
                ->orWhere('fathers_family','LIKE', '%'.$search.'%')
                ->orWhere('mothers_family','LIKE', '%'.$search.'%')
                ->orWhere('run','LIKE', '%'.$search.'%')
                ->orWhere('other_identification','LIKE', '%'.$search.'%');
        }
    }

    public function commune(){
        return $this->belongsTo('App\Commune');
    }

    public function establishment(){
        return $this->belongsTo('App\Establishment');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birthday','sample_at','reception_at','result_at'];

}
