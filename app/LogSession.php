<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'app_name',
        'user_agent',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
