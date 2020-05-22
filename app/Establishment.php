<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      // 'id','name','type','deis','commune_id'
      'id','name','alias','type','code_deis','service','dependency','comuna','commune_code_deis','commune_id'
  ];

  public function commune() {
      return $this->belongsTo('\App\Commune');
  }

  public function suspectCases() {
      return $this->hasMany('App\SuspectCase');
  }

  use SoftDeletes;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];
}
