<?php

namespace Sniper7Kills\Survey\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sniper7Kills\Survey\Models\SurveyGuest;

class UserStubResource extends JsonResource
{
    private $user = null;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->user = $resource;
    }

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
            'guest' => $this->user->getMorphClass() == SurveyGuest::class ? true : false,
            $this->mergeWhen(($this->user->getMorphClass() != SurveyGuest::class), [
                'type' => $this->user->getMorphClass(),
            ]),
            'identifier' => $this->user->getMorphClass() == SurveyGuest::class ? $this->user->id : $this->user->getAuthIdentifier(),
        ];
    }
}
