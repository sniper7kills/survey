<?php

namespace Sniper7Kills\Survey\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmissionRequest extends FormRequest
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
        $survey = $this->route()->parameter('survey');
        $validationArray = [];
        foreach($survey->questions as $question)
        {
            if($question->required)
                $validationArray["question-".$question->id][] = 'required';
            else
                $validationArray["question-".$question->id][] = 'nullable';
            if($question->type == "text")
                $validationArray["question-".$question->id][] = 'string';
            if($question->type == "select" || $question->type == "radio"){
                $validationArray["question-".$question->id][] = 'numeric';
                $validationArray["question-".$question->id][] = Rule::in($question->options->pluck('id')->toArray());
            }
            if($question->type == "checkbox"){
                $validationArray["question-".$question->id][] = 'array';
                $validationArray["question-".$question->id.'.*'][] = 'numeric';
                $validationArray["question-".$question->id.'.*'][] = Rule::in($question->options->pluck('id')->toArray());
            }

        }
        return $validationArray;
    }
}
