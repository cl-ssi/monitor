<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'run',
        'dv',
        'name',
        'email',
        'password',
        'telephone',
        'function',
        'active',
        'laboratory_id',
        'establishment_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function logs() {
        return $this->morphMany('App\Log','model');
    }

    public function vitalSigns() {
        return $this->hasMany('App\SanitaryResidence\VitalSign');
    }

    public function laboratory() {
        return $this->belongsTo('App\Laboratory');
    }

    /**
    * The residence that belong to the user.
    */
    public function residences() {
        return $this->belongsToMany('App\SanitaryResidence\Residence');
    }

    /**
    * The establishment that belong to the user.
    */
    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }
    
    /**
    * The establishments where has access the user.
    */
    public function establishments() {
        return $this->belongsToMany('App\Establishment');
    }

    public function suspectCases() {
        return $this->hasMany('App\SuspectCase');
    }

    public function communes() {
        $ids = array();
        foreach($this->establishments as $estab) {
            $ids[] = $estab->commune->id;
        }
        //print_r($ids);
        return array_values(array_unique($ids));
    }

    public function events() {
        return $this->hasMany('App\Tracing\Event');
    }

    public function bulkLoadRecord() {
      return $this->hasMany('\App\BulkLoadRecord');
    }

    public function logSessions() {
        return $this->hasMany('App\LogSession');
    }

    public function getLastSessionsAttribute() {
        return $this->hasMany('App\LogSession')->limit(30)->orderByDesc('created_at')->get();
    }
}
