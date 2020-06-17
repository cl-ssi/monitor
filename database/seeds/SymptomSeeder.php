<?php

use Illuminate\Database\Seeder;
use App\Tracing\Symptom;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Symptom::create(['name' => 'Fiebre']);
        Symptom::create(['name' => 'Tos']);
        Symptom::create(['name' => 'Mialgias']);
        Symptom::create(['name' => 'Odinofagia']);
        Symptom::create(['name' => 'Anosmia']);
        Symptom::create(['name' => 'Ageusia']);
        Symptom::create(['name' => 'Dolor toráxico']);
        Symptom::create(['name' => 'Diarrea']);
        Symptom::create(['name' => 'Calofrios']);
        Symptom::create(['name' => 'Cefalea']);
        Symptom::create(['name' => 'Dificultad para respirar']);
    }
}
