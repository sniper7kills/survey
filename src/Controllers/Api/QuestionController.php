<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Illuminate\Http\Request;
use Sniper7Kills\Survey\Controllers\Controller;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Resources\QuestionResource;

class QuestionController extends Controller
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
     * @param  Question  $question
     * @return QuestionResource
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Question  $question
     * @return QuestionResource
     */
    public function update(Request $request, Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
