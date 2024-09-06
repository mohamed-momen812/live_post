<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IntegerArray implements ValidationRule
{

    public $momen_property;
    // if you need any values to pass to the class create __construct to recieve them and can access them via $this
    public function __construct($momen_property)
    {
        // Initialize any properties here if needed
        $this->momen_property = $momen_property;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Convert the value to a Laravel collection and check if all elements are integers
        $integerOnly = collect($value)->every(fn($element) => is_int($element));

        if (!$integerOnly) {
            // Fail the validation with a custom message
            $fail($attribute . ' can only be integers ');
        }
    }

}
