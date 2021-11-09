<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;

class BirthdayRule implements Rule
{
    private $p_year;
    private $p_month;
    private $p_day;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($year, $month, $day)
    {
        $this->p_year = $year;
        $this->p_month = $month;
        $this->p_day = $day;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->p_year && $this->p_month && $this->p_day) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '生年月日を正確に選択してください。';
    }
}
