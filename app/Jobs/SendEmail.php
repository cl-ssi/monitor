<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NewPositive;
use App\SuspectCase;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $suspectCase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SuspectCase $suspectCase)
    {
        //
        $this->suspectCase = $suspectCase;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        //$email = new NewPositive($this->suspectCase);
        $emails  = explode(',', env('EMAILS_ALERT'));
        $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
        //dd('entre');
        Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($this->suspectCase));
    }
}
