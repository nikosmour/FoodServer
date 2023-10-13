<?php

namespace App\Http\Requests;

use App\Enum\CardDocumentStatusEnum;
use App\Rules\InArray;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCardApplicationDocumentRequest extends FormRequest
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
        return isset(auth('academics')->user) ? ['files' => 'required', 'descriptions' => 'required', 'ids' => 'required'] : ['status' => ['required', new InArray(CardDocumentStatusEnum::values()->toArray())]];
    }
}
