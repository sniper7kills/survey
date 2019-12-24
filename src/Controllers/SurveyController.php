<?php

namespace Sniper7Kills\Survey\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Models\Answer;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;

class SurveyController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(config('Survey.admin_middleware'))->only(['results','resultsQuestion']);
    }

    /**
     * Show the given Survey
     *
     * @param  Survey  $survey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Survey $survey)
    {
        if($survey->end_at <= Carbon::now())
            return view('survey::ended');
        if(Auth::guest() && !$survey->guests)
            return view('survey::no-guests');
        return view('survey::view', ['survey' => $survey]);
    }

    /**
     * Submit the given Survey
     *
     * @param  Survey  $survey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submit(Request $request, Survey $survey)
    {
        if(!Auth::guest()){
            $userable = Auth::user();
        }
        else{
            $userable = SurveyGuest::firstOrCreate(['ip'=>$request->getClientIp(),'agent'=>$request->userAgent()]);
        }

        if($survey
                ->responses()
                ->where('userable_id',$userable->id)
                ->where('userable_type',get_class($userable))
                ->count() > 0)
        {
            return view('survey::already-submitted');
        }

        $response = new Response();
        $response->userable()->associate($userable);
        $survey->responses()->save($response);

        foreach($survey->questions as $question)
        {
            $answer = new Answer();
            $answer->question()->associate($question);
            $response->answers()->save($answer);
            if($question->type == "text") {
                $answer->answer = $request->get($question->id);
                $answer->save();
            }elseif ($question->type == "radio" || $question->type == "select"){
                $answer->options()->attach($request->get($question->id));
            }elseif ($question->type == "checkbox"){
                foreach($request->get($question->id) as $optionId)
                {
                    $answer->options()->attach($optionId);
                }
            }
        }

        return view('survey::thanks');
    }

    public function results(Survey $survey)
    {
        return view('survey::results', ['survey'=>$survey]);
    }

    public function resultsQuestion(Survey $survey, Question $question)
    {
        $answers = $question->answers()->paginate(20);
        return view('survey::results-question', ['survey'=>$survey, 'question'=>$question, 'answers'=>$answers]);
    }
}