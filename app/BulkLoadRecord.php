<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkLoadRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    protected $table = 'bulk_load_records';
}
