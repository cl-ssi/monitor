<?php

namespace App\Console\Commands;

use App\SuspectCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SuspectCasesUploadPdfGcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SuspectCases:uploadPdfGcs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $suspectCaseId = 987654;
        $file = Storage::disk('local')->get('suspect_cases/'. $suspectCaseId .'.pdf');
        Storage::disk('gcs')->put('esmeralda/suspect_cases/'. $suspectCaseId, $file);
        return 0;
    }
}
