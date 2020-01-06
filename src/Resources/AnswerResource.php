<?php

namespace Sniper7Kills\Survey\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sniper7Kills\Survey\Models\SurveyGuest;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question->id,
            $this->mergeWhen(!is_null($this->answer), [
                'answer' => $this->answer,
            ]),
            $this->mergeWhen(is_null($this->answer), [
                'options' => $this->options->pluck('id'),
            ]),
        ];
    }
}
