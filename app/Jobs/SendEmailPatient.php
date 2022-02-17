<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\SuspectCase;
use App\Mail\NewNegative;

class SendEmailPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $suspectCase;
    public $generatePdf;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SuspectCase $suspectCase)
    {
        $this->suspectCase = $suspectCase;
    }

    public function retryAfter(){
        return 20;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email  = $this->suspectCase->patient->demographic->email;

        if ($this->suspectCase->laboratory->pdf_generate == 1) {
            $pdf = \PDF::loadView('lab.results.result', ['case' => $this->suspectCase]);
            $message = new NewNegative($this->suspectCase);
            $message->attachData($pdf->output(), $this->suspectCase->id . '.pdf');
            Mail::to($email)->send($message);
        } else {
            if ($this->suspectCase->file == 1) {
                $message = new NewNegative($this->suspectCase);
                $message->attachFromStorage('suspect_cases/' . $this->suspectCase->id . '.pdf', $this->suspectCase->id . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
                Mail::to($email)->send($message);
            } else {
                $message = new NewNegative($this->suspectCase);
                Mail::to($email)->send($message);
            }
        }
    }
}
