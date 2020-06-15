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

    public function contact_patient() {
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
            case "brother in law":
                return 'Cuñado';
                break;
            case "husband":
                return 'Esposo';
                break;
            case "brother":
                return 'Hermano';
                break;
            case "son":
                return 'Hijo';
                break;
            case "grandchild":
                return 'Nieto/a';
                break;
            case "father":
                return 'Padre';
                break;
            case "cousin":
                return 'Primo/a';
                break;
            case "nephew":
                return 'Sobrino';
                break;
            case "father in law":
                return 'Suegro';
                break;
            case "uncle":
                return 'Tío';
                break;
            case "son in law":
                return 'Yerno';
                break;
            case "other":
                return 'Otro';
                break;

            case "grandmother":
                return 'Abuela';
                break;
            case "sister in law":
                return 'Cuñada';
                break;
            case "wife":
                return 'Esposa';
                break;
            case "Sister":
                return 'Hermana';
                break;
            case "daughter":
                return 'Hija';
                break;
            case "mother":
                return 'Madre';
                break;
            case "niece":
                return '';
                break;
            case "father in law":
                return 'Suegro';
                break;
            case "uncle":
                return 'Tío';
                break;
            case "son in law":
                return 'Yerno';
                break;
            case "other":
                return 'Otro';
                break;
        }
    }

}
