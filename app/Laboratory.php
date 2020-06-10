<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'external', 'minsal_ws', 'token_ws', 'pdf_generate', 'cod_deis',  'commune_id'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }

    public function commune() {
        return $this->belongsTo('App\Commune');
    }
}
