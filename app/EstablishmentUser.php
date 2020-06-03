<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class EstablishmentUser extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'establishment_id','user_id',
    ];

    protected $table = 'establishment_user';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }
}
