<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function requests() {
        return $this->hasMany('App\Tracing\Request');
    }

    protected $table = 'tracing_request_types';
}
