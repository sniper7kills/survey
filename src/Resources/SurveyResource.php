<?php

namespace Sniper7Kills\Survey\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'name' => $this->name,
            'description' => $this->description,
            'key' => $this->getRouteKey(),
            'questions' => QuestionResource::collection($this->questions),
            $this->mergeWhen(
                (!Auth::guest()
                    && method_exists(Auth::user(),'isASurveyAdmin')
                    && Auth::user()->isASurveyAdmin()
                ),
                [
                    'available_at' => is_null($this->available_at) ? null : $this->available_at->format(Carbon::ISO8601),
                    'available_until' => is_null($this->available_until)? null :$this->available_until->format(Carbon::ISO8601)
                ]
            )
        ];
    }
}
