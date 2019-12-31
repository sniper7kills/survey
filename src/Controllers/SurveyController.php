<?php


namespace Sniper7Kills\Survey\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Models\Answer;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;
use Sniper7Kills\Survey\Requests\SurveyRequest;

class SurveyController extends BaseController
{

    public function view(Request $request, Survey $survey)
    {
        if(!is_null($survey->end_at) && $survey->end_at < Carbon::now())
        {
            return response()->view('survey::ended',[],403);
        }

        if(!$survey->guests && Auth::guest())
            return response()->view('survey::no-guests',[],403);

        if(Auth::guest())
        {
            $user = SurveyGuest::current();
        }else{
            $user = Auth::user();
        }

        if($survey->responses()->where('userable_id',$user->getKey())->where('userable_type',$user->getMorphClass())->count() > 0)
        {
            return response()->view('survey::already-submitted',[],403);
        }

        return view('survey::view', ['survey'=>$survey]);
    }

    public function submit(SurveyRequest $request, Survey $survey)
    {
        if(!is_null($survey->end_at) && $survey->end_at < Carbon::now())
        {
            return response()->view('survey::ended',[],403);
        }

        if(Auth::guest())
        {
            $user = SurveyGuest::current();
        }else{
            $user = Auth::user();
        }

        if($survey->responses()->where('userable_id',$user->getKey())->where('userable_type',$user->getMorphClass())->count() > 0)
        {
            return response()->view('survey::already-submitted',[],403);
        }

        $request = $request->validated();

        $response = new Response();
        $response->userable()->associate($user);
        $survey->responses()->save($response);
        foreach($survey->questions as $question)
        {
            $answer = new Answer();
            $answer->question()->associate($question);
            $response->answers()->save($answer);
            switch ($question->type)
            {
                case 'text':
                    $answer->answer = $request[$question->id];
                    $answer->save();
                    break;
                case 'checkbox':
                    foreach($request[$question->id] as $optionId)
                    {
                        $answer->options()->attach($optionId);
                    }
                    break;
                case 'radio':
                case 'select':
                    $answer->options()->attach($request[$question->id]);
                    break;
                default:
                    break;
            }
        }

        return view('survey::thanks');
    }

}