<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ReportBackup as ReportBackup2;
use App\SuspectCase;

class ReportBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Report:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea que genera backup de reporte.';

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
     * @return mixed
     */
    public function handle()
    {
      $cases = SuspectCase::with('patient')->with('patient.demographic')->get();
      $reportBackup = new ReportBackup2();
      $reportBackup->data = json_encode($cases);
      $reportBackup->save();
    }
}
