<?php

namespace App;

use Patient;
use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

class ContactPatient extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    protected $fillable = [
        'patient_id', 'contact_id', 'last_contact_at', 'category', 'relationship', 'live_together', 'comment', 'index', 'user_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient', 'contact_id');
    }

    public function self_patient() {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getLiveTogetherDescAttribute(){
        switch ($this->live_together) {
            case 1: return 'SI'; break;
            case 0: return 'NO'; break;
        }
    }

    public function getLastContactDateAttribute(){
        return Carbon::parse($this->last_contact_at)->format('Y-m-d\TH:i');
    }

    public function getCategoryDescAttribute(){
      switch ($this->category) {
          case "institutional":
              return 'Institucional';
              break;
          case "ocupational":
              return 'Laboral';
              break;

          case "passenger":
              return 'Pasajero';
              break;
          case "social":
              return 'Social';
              break;

          case "waiting room":
              return 'Sala de Espera';
              break;
          case "family":
              return 'Familiar/domiciliario';
              break;

          case "intradomiciliary":
              return 'Familiar/domiciliario';
              break;

          case "functionary":
              return 'Personal de Salud';
              break;
      }
    }

    public function getCategoryEpivigilaAttribute(){
      switch ($this->category) {
          case "institutional":
              return 'institucional';
              break;
          case "ocupational":
              return 'laboral';
              break;
          case "passenger":
              return 'pasajero';
              break;
          case "social":
              return 'social';
              break;
          case "waiting room":
              return 'sala_espera';
              break;
          case "intradomiciliary":
          case "family":
              return 'familiar';
              break;
          case "functionary":
              return 'personal_salud';
              break;
      }
    }

    public function getRelationshipNameAttribute(){
        switch ($this->relationship) {
            case "grandfather":
                return 'Abuelo';
                break;
            case "grandmother":
                return 'Abuela';
                break;

            case "sister in law":
                return 'Cuñada';
                break;
            case "brother in law":
                return 'Cuñado';
                break;

            case "wife":
                return 'Esposa';
                break;
            case "husband":
                return 'Esposo';
                break;

            case "sister":
                return 'Hermana';
                break;
            case "brother":
                return 'Hermano';
                break;

            case "daughter":
                return 'Hija';
                break;
            case "son":
                return 'Hijo';
                break;

            case "mother":
                return 'Madre';
                break;
            case "father":
                return 'Padre';
                break;

            case "cousin":
                return 'Primo/a';
                break;

            case "niece":
                return 'Sobrina';
                break;
            case "nephew":
                return 'Sobrino';
                break;

            case "mother in law":
                return 'Suegra';
                break;
            case "father in law":
                return 'Suegro';
                break;

            case "aunt":
                return 'Tía';
                break;
            case "uncle":
                return 'Tío';
                break;

            case "grandchild":
                return 'Nieto/a';
                break;

            case "daughter in law":
                return 'Nuera';
                break;
            case "son in law":
                return 'Yerno';
                break;

            case "girlfriend":
                return 'Pareja';
                break;
            case "boyfriend":
                return 'Pareja';
                break;

            case "other":
                return 'Otro Parentesco u Relación';
                break;
        }
    }
    public function getRelationshipNameEpivigilaAttribute(){
        switch ($this->relationship) {
            case "grandmother":
            case "sister in law":
            case "brother in law":
            case "cousin":
            case "niece":
            case "nephew":
            case "mother in law":
            case "father in law":
            case "aunt":
            case "uncle":
            case "grandchild":
            case "daughter in law":
            case "son in law":
            case "grandfather":
                return 'otro_familiar';
                break;

            case "husband":
            case "girlfriend":
            case "boyfriend":
            case "wife":
                return 'pareja';
                break;

            case "brother":
            case "sister":
                return 'hermano_a';
                break;

            case "son":
            case "daughter":
                return 'hijo_a';
                break;

            case "father":
            case "mother":
                return 'madre_padre';
                break;

            case "other":
                return 'otra_relacion';
                break;
        }
    }

}
