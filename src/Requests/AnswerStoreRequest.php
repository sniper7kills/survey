<?php

namespace Sniper7Kills\Survey\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sniper7Kills\Survey\Models\Survey;

class AnswerStoreRequest extends FormRequest
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
            'response_id' => ['exists:responses,id','required'],
            'question_id' => ['exists:questions,id','required'],
            'answer' => ['nullable', 'string', 'required_without:options'],
            'options' => ['nullable', 'array', 'required_without:answer'],
            'options.*' => 'exists:options,id'
        ];
    }
}
