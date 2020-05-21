<?php

namespace App\Lab\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Covid19 extends Model
{
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
        'telephone','email','address','commune',
        'origin','origin_commune',
        'sample_type', 'sample_at',
        'reception_at', 'result_at', 'result',
        'user_id','recpetor_id','validator_id'
    ];

    function getIdentifierAttribute() {
        if(isset($this->run) and isset($this->dv)) {
            return $this->run . '-' . $this->dv;
        }
        else  {
            return 'E:'.$this->other_identification;
        }
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birthday','sample_at','reception_at','result_at'];

}
