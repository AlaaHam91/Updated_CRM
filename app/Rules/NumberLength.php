<?php

namespace App\Rules;

use App\Models\ContactConfiguration;
use App\Models\NumberConfiguration;
use Illuminate\Contracts\Validation\Rule;

class NumberLength implements Rule
{
    private $data;
    private $length;

    /**
     * Create a new rule instance.
     *
     * @return void
     */


    public function __construct($data)
    {
        $this->data = $data;
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

        $x = explode('.',$attribute)[1];
        $va = ContactConfiguration::with('configurable')
            ->whereHasMorph('configurable',[NumberConfiguration::class],function ($query)
                   use($x) {
            return $query->where('country_id', $this->data[$x]['country_id']);
        })
            ->where('communication_method_id',$this->data[$x]['communication_method_id'])
            ->first();


        $config = null;
        if(isset($va))
            $config = $va->load('configurable');

        if($config != null)
        {
            $this->length = $config->configurable->length;
            if(strlen($value) === $this->length)
                return true;
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute length must be '.$this->length.' digits';
    }
}
