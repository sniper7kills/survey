<?php

namespace Sniper7Kills\Survey\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
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
            'survey_id' => ['required','exists:surveys,id'],
            'question' => ['text','required'],
            'type'  => ['required', Rule::in(['text','radio','select','checkbox'])]
        ];
    }
}
