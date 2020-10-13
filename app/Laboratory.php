<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Laboratory
 *
 * @mixin Builder
 */
class Laboratory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_openagora', 'name', 'external', 'minsal_ws', 'token_ws', 'pdf_generate', 'cod_deis',  'commune_id', 'alias', 'director_id'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }

    public function commune() {
        return $this->belongsTo('App\Commune');
    }

    public function director() {
        return $this->belongsTo('App\User');
    }
}
