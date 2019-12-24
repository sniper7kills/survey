<?php

namespace Sniper7Kills\Survey\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $returnArray = [
            'id' => $this->id,
            'question' => $this->question,
            'type' => $this->type,
        ];
        if($this->type != "text")
            $returnArray['options'] = OptionResource::collection($this->options);

        return $returnArray;
    }
}
