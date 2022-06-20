<?php

namespace App\Console\Commands;

use App\SuspectCase;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Str;

class SuspectCasesUploadPdfGcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SuspectCases:uploadPdfGcs {p1_id_case_from} {p2_id_case_to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga pdf de los casos a gcs en un rango de ids de suspect cases';

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
     * @return int
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $idSuspectCaseFrom = $this->argument('p1_id_case_from');
        $idSuspectCaseTo = $this->argument('p2_id_case_to');

        $suspectCases = SuspectCase::query()
            ->whereBetween('id', [
                $idSuspectCaseFrom,
                $idSuspectCaseTo
            ])->get();

        $count = 0;
        SuspectCase::disableAuditing();
        
        foreach ($suspectCases as $suspectCase) {
            $filePath = 'suspect_cases/' . $suspectCase->id . '.pdf';
            
            if ($suspectCase->file == 1 && Storage::disk('local')->exists($filePath) ) {
                $file = Storage::disk('local')->get($filePath);
                $filename = Str::uuid();
                
                $suspectCase->filename_gcs = $filename;
                $suspectCase->save();
                
                $succesfull = Storage::disk('gcs')->put('esmeralda/suspect_cases/' . $filename . '.pdf', $file, ['CacheControl' => 'no-cache, must-revalidate']);
                if ($succesfull) {
//                    Storage::move($filePath, 'suspect_cases_bkp/' . $suspectCase->id . '.pdf');
                    $count++;
                }
            }
        }
        
        SuspectCase::enableAuditing();

        $this->info("Se cargaron $count archivos");
        return 0;
    }
}
