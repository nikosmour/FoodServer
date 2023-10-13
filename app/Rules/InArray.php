<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InArray implements Rule
{
    protected $allowedValues;

/**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
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
        return in_array($value, $this->allowedValues);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be one of ' . implode(', ', $this->allowedValues);
    }
}
