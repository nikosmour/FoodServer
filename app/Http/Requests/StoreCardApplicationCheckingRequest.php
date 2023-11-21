<?php

namespace App\Http\Requests;

use App\Enum\CardDocumentStatusEnum;
use App\Enum\CardStatusEnum;
use App\Models\CardApplication;
use App\Rules\InArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardApplicationCheckingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', new InArray(CardStatusEnum::values()->toArray())],
            'card_application_id' => ['required',"exists:card_applications,id"],
            'expiration_date'=>['date',
                Rule::requiredIf($this->input('status') == CardStatusEnum::ACCEPTED->value),
                'after_or_equal:'. CardApplication::whereId($this->input('card_application_id'))
                    ->select('expiration_date')->value('expiration_date')
            ],
            'card_application_staff_comment'=>Rule::requiredIf($this->input('status') != CardStatusEnum::ACCEPTED->value)


        ];
    }
}
