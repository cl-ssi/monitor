<?php

namespace App;

use SuspectCase;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id','file','name','suspect_case_id'
  ];

  /**
  * The table associated with the model.
  *
  * @var string
  */
  protected $table = 'files';

  //relaciones
  public function suspectCase() {
      return $this->belongsTo('App\SuspectCase');
  }
}
