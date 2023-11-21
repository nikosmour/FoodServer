<?php

namespace App\Http\Requests;

use App\Enum\CardDocumentStatusEnum;
use App\Enum\CardStatusEnum;
use App\Rules\InArray;
use Illuminate\Foundation\Http\FormRequest;

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
            'card_application_id' => ['required',"exists:card_applications,id"]

        ];
    }
}
