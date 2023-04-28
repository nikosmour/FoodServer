<?php

namespace App\Http\Requests;

use App\Enum\UserAbilityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEntryCheckingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * If the user has the ability to make check in the Entry.
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->hasAbility(UserAbilityEnum::ENTRY_CHECK);
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string,string>
     */
    public function rules(): array
    {
        return [
            'academic_id'=>["required", "integer", "exists:coupon_owners,academic_id"]
            ];
    }


}
