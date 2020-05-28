<?php

namespace App\SanitaryResidence;

use Illuminate\Database\Eloquent\Model;

class ResidenceUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'residence_id','user_id',
    ];

    protected $table = 'residence_user';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function residence() {
        return $this->belongsTo('App\SanitaryResidence\Residence');
    }
}
