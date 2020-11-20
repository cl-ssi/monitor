<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingPatient extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'name', 'fathers_family', 'mothers_family', 'address',
        'region_id', 'commune_id', 'email', 'telephone', 'status'];

    public function commune() {
        return $this->belongsTo('App\Commune');
    }
}
