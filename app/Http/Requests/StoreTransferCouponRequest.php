<?php

namespace App\Http\Requests;

use App\Enum\MealPlanPeriodEnum;
use App\Enum\UserAbilityEnum;
use App\Rules\AtLeastOneNoZero;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->hasAbility(UserAbilityEnum::COUPON_OWNERSHIP);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        $rules['receiver_id'] = ["required","integer","exists:coupon_owners,academic_id"];
        $periods = MealPlanPeriodEnum::names();
        foreach ($periods as $period) {
            $rules[$period] = ['required',
                'integer', 'min:0'
            ];
        }
        $rules[$periods[0]][] = new AtLeastOneNoZero(...$periods);
        return $rules;
    }
}