<?php

namespace App\Pharmacies;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'date', 'pharmacy_id', 'pharmacy', 'establishment_id', 'establishment', 'notes', 'user_id', 'user', 'created_at'
  ];

  protected $table = 'frm_dispatches';

  //relaciones
  public function dispatchItems()
  {
    return $this->hasMany('App\Pharmacies\DispatchItem');
  }

  protected $dates = ['date'];
}
