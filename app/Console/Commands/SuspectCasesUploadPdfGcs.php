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
    protected $description = 'Carga pdf de los casos a gcs';

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
        $suspectCases = SuspectCase::query()->limit(10)->get();

        $count = 0;
        foreach ($suspectCases as $suspectCase) {
            if($suspectCase->file == 1){
                $file = Storage::disk('local')->get('suspect_cases/'. $suspectCase->id .'.pdf');
                $succesfull = Storage::disk('gcs')->put('esmeralda/suspect_cases/'. $suspectCase->id, $file);
                if($succesfull) $count++;
            }
        }

        echo "Se cargaron $count archivos";
        return 0;
    }
}
