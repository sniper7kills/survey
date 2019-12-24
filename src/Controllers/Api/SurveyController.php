<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Illuminate\Http\Request;
use Sniper7Kills\Survey\Controllers\Controller;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Resources\SurveyResource;

class SurveyController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(config('Survey.admin_middleware'))->only(['index','store','update','destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Survey  $survey
     * @return SurveyResource
     */
    public function show(Survey $survey)
    {
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Survey  $survey
     * @return SurveyResource
     */
    public function update(Request $request, Survey $survey)
    {
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        //
    }
}
