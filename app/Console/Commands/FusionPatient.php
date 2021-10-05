<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Patient;

class FusionPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fusion:patient {p1_id} {p2_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fusion Patient_1 into Patient_2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $patient1 = Patient::find($this->argument('p1_id'));
        $patient2 = Patient::find($this->argument('p2_id'));

        foreach($patient1->suspectCases as $sc) {
            $sc->update(['patient_id' => $patient2->id]);
        }

        foreach($patient1->audits as $audit) {
            $audit->update(['auditable_id' => $patient2->id]);
        }

        $patient1->demographic->delete();
        $patient1->delete();

        print_r($patient1->toArray());
        return 0;
    }
}
