<?php

namespace App\Rules\Store;

use Illuminate\Contracts\Validation\Rule;

class PostDateRule implements Rule
{
    private $p_date;
    private $p_hour;
    private $p_minute; 
    private $p_msg; 

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $hour, $min, $msg)
    { 
        $this->p_date = $date;
        $this->p_hour = $hour;
        $this->p_minute = $min; 
        $this->p_msg = $msg;
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
        if(($this->p_hour != null && $this->p_minute != null && $this->p_date != null) || ($this->p_hour == null && $this->p_minute == null && $this->p_date == null)) {
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
        return '掲載期間(' . $this->p_msg . ')を正確に選択してください。';
    }
}
