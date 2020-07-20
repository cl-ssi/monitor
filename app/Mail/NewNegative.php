<?php

namespace App\Mail;

use App\SuspectCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewNegative extends Mailable
{
    use Queueable, SerializesModels;

    public $suspectCase;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SuspectCase $suspectCase)
    {
        $this->suspectCase = $suspectCase;
        // if ($this->suspectCase->laboratory) {
        //     if ($this->suspectCase->laboratory->pdf_generate == 1) {
        //         $case = $this->suspectCase;
        //         $this->pdf = \PDF::loadView('lab.results.result', compact('case'));
        //         $this->file_name = $suspectCase->id . '.pdf';
        //     }
        // }

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.newnegative')
                    ->subject('Resultado de Examen');
    }
}
