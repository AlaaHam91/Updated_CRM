<?php

namespace App\Rules;

use App\Models\ContactConfiguration;
use App\Models\NumberConfiguration;
use Illuminate\Contracts\Validation\Rule;

class PhonePrefixes implements Rule
{
    private $data;
    private $p;
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

        $data = ContactConfiguration::with('configurable')
            ->whereHasMorph('configurable',[NumberConfiguration::class],function ($query)
            use($x) {
                return $query->where(
                    [
                        ['country_id','=',$this->data[$x]['country_id']],
                        ['city_id','=',$this->data[$x]['city_id']]
                    ]
                );
            })
            ->where('communication_method_id',$this->data[$x]['communication_method_id'])
            ->first();

        $config = null;
        if(isset($data))
            $config = $data->load('configurable');

        if($config != null)
        {

            $prefixes = [];
            if($config->configurable->prefixes != null){
                $prefixes = explode(',',$config->configurable->prefixes);
                $this->p = $config->configurable->prefixes;
            }
            $flag = false;
            if(count($prefixes) > 0 )
            {
                foreach($prefixes as $prefix)
                {
                    $first_digits = substr($value, 0, strlen($prefix));
                    if($first_digits === $prefix)
                    {
                        $flag = true;
                        break;
                    }
                }

            }
            return $flag;
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
        return 'The :attribute must start with one of '.$this->p;
    }
}
