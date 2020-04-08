<?php

namespace App\Pharmacies;

use Illuminate\Database\Eloquent\Model;

class DispatchItem extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'barcode', 'dispatch_id', 'product_id', 'product', 'amount', 'unity', 'due_date','batch', 'created_at'
  ];

  protected $table = 'frm_dispatch_items';

  //relaciones
  public function dispatch()
  {
    return $this->belongsTo('App\Pharmacies\Dispatch');
  }
}
