<?php

namespace App\Parameters;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public static function get($module, $parameter) {
        return Parameter::where('module', $module)
                        ->where('parameter', $parameter)
                        ->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module', 'parameter', 'value', 'description'
    ];

    protected $table = 'cfg_parameters';
}
