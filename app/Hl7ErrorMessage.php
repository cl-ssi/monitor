<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hl7ErrorMessage extends Model
{
    use SoftDeletes;
    //

    protected $fillable = ['alert_id','channel_name','connector_name','message_id','error','error_message'];
}
