<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Controllers\BaseController;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Resources\QuestionResource;

class QuestionController extends BaseController
{
    public function __construct()
    {
        $this->middleware(SurveyAdminMiddleware::class)->except('show');
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
     * @param  Question  $question
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|QuestionResource
     */
    public function show(Question $question)
    {
        if($question->survey->available_at > Carbon::now() || $question->survey->available_at == null)
        {
            if(Auth::guest())
                return response('',403);
            else if(!method_exists(Auth::user(),'isASurveyAdmin'))
                return response('',403);
            else if(!Auth::user()->isASurveyAdmin())
                return response('',403);
        }
        if($question->survey->available_until != null && $question->survey->available_until < Carbon::now())
        {
            if(Auth::guest())
                return response('',403);
            else if(!method_exists(Auth::user(),'isASurveyAdmin'))
                return response('',403);
            else if(!Auth::user()->isASurveyAdmin())
                return response('',403);
        }

        return QuestionResource::make($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response('',204);
    }
}
