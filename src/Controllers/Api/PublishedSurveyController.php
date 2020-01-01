<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Sniper7Kills\Survey\Controllers\BaseController;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Requests\PublishedSurveyStoreRequest;
use Sniper7Kills\Survey\Resources\SurveyResource;

class PublishedSurveyController extends BaseController
{
    public function __construct()
    {
        $this->middleware(SurveyAdminMiddleware::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PublishedSurveyStoreRequest $request
     * @return SurveyResource
     */
    public function store(PublishedSurveyStoreRequest $request)
    {
        $request = $request->validated();
        $survey = Survey::findOrFail($request['survey_id']);
        $survey->publishSurvey();

        return SurveyResource::make($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Survey $publishedSurvey
     * @return SurveyResource
     */
    public function destroy(Survey $publishedSurvey)
    {
        $publishedSurvey->unpublishSurvey();

        return SurveyResource::make($publishedSurvey);
    }
}
