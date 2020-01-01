<?php

namespace Sniper7Kills\Survey\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveyStoreRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['string','nullable'],
            'key' => ['nullable', Rule::in(['id','slug'])],
            'available_until' => ['nullable', 'date']
        ];
    }
}
