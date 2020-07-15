<?php

namespace App\Rules;

use App\SuspectCase;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class UniqueSampleDateByPatient implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $sample_at;

    public function __construct($sample_at)
    {
        $this->sample_at = Carbon::parse($sample_at);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $id
     * @return bool
     */
    public function passes($attribute, $id)
    {

        if ($id != null){

            return !SuspectCase::where('patient_id', $id)
                ->whereDay('sample_at', '=', $this->sample_at->day)
                ->whereMonth('sample_at', '=', $this->sample_at->month)
                ->whereYear('sample_at', '=', $this->sample_at->year)
                ->exists();
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe una muestra para este paciente en el día ' . $this->sample_at->format('d-m-Y') . '';
    }
}
