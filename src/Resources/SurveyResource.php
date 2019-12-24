<?php

namespace Sniper7Kills\Survey\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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
            'name' => $this->description,
            'guests_allowed' => $this->guests,
            'questions' => QuestionResource::collection($this->questions)
        ];
    }
}
