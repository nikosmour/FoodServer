<?php

namespace App\Http\Requests;

use App\Enum\MealPlanPeriodEnum;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        $rules['receiver_id'] = ["required", "integer", "exists:coupon_owners,academic_id"];
        $periods = MealPlanPeriodEnum::names();
        foreach ($periods as $period) {
            $rules[$period] = ['required',
                'integer', 'min:0'
            ];
        }
        $rules[$periods[0]][] = new AtLeastOneNoZero(...$periods);
        $couponOwner= \App\Models\CouponOwner::find(auth()->user()->academic_id);
        foreach ($periods as $period) {
            $rules[$period] = ['required',
                'integer', 'max:'.$couponOwner[$period]
            ];
        }
        return $rules;
    }
}
