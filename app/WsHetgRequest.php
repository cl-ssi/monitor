<?php /** @noinspection ALL */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class WsHetgRequest extends Model
{
    protected $fillable = [];

    const TYPE_LOGIN = 'login';
    const TYPE_REQUEST = 'request';
}
