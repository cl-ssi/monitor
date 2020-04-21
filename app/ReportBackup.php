<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportBackup extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'data','created_at'
  ];

  /**
  * The table associated with the model.
  *
  * @var string
  */
  protected $table = 'report_backups';

}
