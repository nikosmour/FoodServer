<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class AtLeastOneNoZero implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    private array $params = [];

    public function __construct(...$params)
    {
        $this->params = $params;
    }

    /**
     * Create a new rule instance.
     *
     * @param $param
     * @return AtLeastOneNoZero
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->params as $param)
            if (0 < $this->data[$param])
                return;
        $fail(__('validation.utlist_one_greater_than_zero'));
    }
}
