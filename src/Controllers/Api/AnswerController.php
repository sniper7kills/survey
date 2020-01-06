<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Controllers\BaseController;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;
use Sniper7Kills\Survey\Models\Answer;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;
use Sniper7Kills\Survey\Requests\AnswerStoreRequest;
use Sniper7Kills\Survey\Requests\ResponseStoreRequest;
use Sniper7Kills\Survey\Resources\AnswerResource;
use Sniper7Kills\Survey\Resources\ResponseResource;

class AnswerController extends BaseController
{
    public function __construct()
    {
        $this->middleware(SurveyAdminMiddleware::class)->except('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AnswerStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|AnswerResource
     */
    public function store(AnswerStoreRequest $request)
    {
        $request = $request->validated();

        $response = Response::find($request['response_id']);
        $question = Question::find($request['question_id']);

        if($question->survey->id != $response->survey->id) {
            return response('',403);
        }else if($response->survey->available_at > Carbon::now() || $response->survey->available_at == null) {
            return response('',403);
        }else if($response->survey->available_until != null && $response->survey->available_until < Carbon::now()) {
            return response('',403);
        }else if($response->answers()->where('question_id',$question->id)->count() > 0) {
            return response('',403);
        }

        if($question->type != 'text'){
            if(!is_array($request['options']))
                return response('',403);
            foreach($request['options'] as $optionID)
            {
                if(in_array($optionID, $question->options->pluck('id')))
                    return response('',403);
            }
        }else{
            if(!key_exists('answer',$request))
                return response('',403);
        }

        if(Auth::guest()){
            if(!$response->survey->guests)
                return response('',403);
            $user = SurveyGuest::current();
        }else
            $user = Auth::user();

        if($response->userable->id != $user->id || $response->userable->getMorphClass() != $user->getMorphClass())
            return response('',403);

        $answer = new Answer();
        $answer->question()->associate($question);
        $response->answers()->save($answer);

        if($question->type == 'text')
            $answer->answer = $request['answer'];
        else{
            foreach($request['options'] as $optionID){
                $answer->options()->attach(Option::find($optionID));
            }
        }

        return AnswerResource::make($answer);
    }

    /**
     * Display the specified resource.
     *
     * @param Answer $answer
     * @return AnswerResource
     */
    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }
}
