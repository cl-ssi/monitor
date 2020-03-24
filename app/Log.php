<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'old','new','diferences','model_id', 'model_type', 'user_id'
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function setDiferencesAttribute(){
        $old = json_decode($this->old, TRUE);
        $new = json_decode($this->new, TRUE);

        if(is_array($old)){
            $aReturn = array();
            foreach ($new as $mKey => $mValue) {
                if (array_key_exists($mKey, $old)) {
                    if (is_array($mValue)) {
                        $aRecursiveDiff = arrayRecursiveDiff($mValue, $old[$mKey]);
                        if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                    } else {
                        if ($mValue != $old[$mKey]) {
                            $aReturn[$mKey] = $mValue;
                        }
                    }
                } else {
                    $aReturn[$mKey] = $mValue;
                }
            }
            $this->attributes['diferences'] = json_encode($aReturn);
        }
        else {
            $this->attributes['diferences'] = json_encode($new);
        }

    }

    public function getDiferencesArrayAttribute(){
        $diferences = json_decode($this->diferences,TRUE);
        if(isset($diferences['updated_at'])){
            unset($diferences['updated_at']);
        }
        return $diferences;
    }
}
