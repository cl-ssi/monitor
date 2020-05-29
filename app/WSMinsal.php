<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WSMinsal extends Model
{
    //

    public static function ws(Request $request) {
        return 'hola';
    }
}
