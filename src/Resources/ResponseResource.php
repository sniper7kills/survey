<?php

namespace Sniper7Kills\Survey\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sniper7Kills\Survey\Models\SurveyGuest;

class ResponseResource extends JsonResource
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
            'user' => UserStubResource::make($this->userable)
        ];
    }
}
