<?php

namespace Sniper7Kills\Survey\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SurveyRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('survey')->guests)
            return true;
        elseif(Auth::guest())
            return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $survey = $this->route('survey');
        $rules = [];

        foreach($survey->questions as $question)
        {
            $rules[$question->id] = [];
            if($question->required)
                $rules[$question->id][] = 'required';
            else
                $rules[$question->id][] = 'nullable';

            switch ($question->type)
            {
                case 'text':
                    $rules[$question->id][] = 'string';
                    break;
                case 'checkbox':
                    $rules[$question->id][] = 'array';
                    $rules[$question->id.".*"][] = Rule::in($question->options->pluck('id'));
                    break;
                case 'select':
                case 'radio':
                    $rules[$question->id][] = Rule::in($question->options->pluck('id'));
                    break;
                default:
                    break;
            }
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $survey = $this->route('survey');
        $messages = [];

        foreach($survey->questions as $question)
        {
            $messages[$question->id] = [];
            if($question->required)
                $messages[$question->id.'.required'] = 'This question is required.';

            switch ($question->type)
            {
                case 'text':
                    $messages[$question->id.'.string'] = 'This question needs to be a string.';
                    break;
                case 'checkbox':
                    $messages[$question->id.'.array'] = 'This question needs to be submitted as an array.';
                    $messages[$question->id.'.*.in'] = 'This question has invalid options selected.';
                    break;
                case 'select':
                case 'radio':
                    $messages[$question->id.'.in'] = 'This question has invalid options selected.';
                    break;
                default:
                    break;
            }
        }
        return $messages;
    }
}
