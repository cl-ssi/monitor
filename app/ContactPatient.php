<?php

namespace App;

use Patient;
use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactPatient extends Model
{
    use softDeletes;

    protected $fillable = [
        'patient_id', 'contact_id', 'comment', 'relationship', 'index','user_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Patient', 'contact_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getRelationshipNameAttribute(){
        switch ($this->relationship) {
            case "grandfather":
                return 'Abuelo';
                break;
            case "grandmother":
                return 'Abuela';
                break;

            case "coworker":
                return 'Compañero/a de trabajo';
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

            case "neighbour":
                return 'Vecino/a';
                break;

            case "other":
                return 'Otro Parentesco u Relación';
                break;
        }
    }

}
