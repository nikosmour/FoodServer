<?php

namespace App\Rules;

use App\Models\Academic;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IsUserActive implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $User = Academic::whereAcademicId($value)->select('is_active')->first();
        if (!$User)
            $fail(__('validation.exists'));
        elseif (!$User->is_active)
            $fail(__('validation.not_active'));

    }
}
