<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Illuminate\Http\Request;
use Sniper7Kills\Survey\Controllers\Controller;
use Sniper7Kills\Survey\Models\Answer;
use Sniper7Kills\Survey\Resources\AnswerResource;

class AnswerController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(config('Survey.admin_middleware'))->except(['store']);
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
     * @param  Answer  $answer
     * @return AnswerResource
     */
    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Answer  $answer
     * @return AnswerResource
     */
    public function update(Request $request, Answer $answer)
    {
        return new AnswerResource($answer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
