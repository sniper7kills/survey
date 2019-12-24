<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Illuminate\Http\Request;
use Sniper7Kills\Survey\Controllers\Controller;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Resources\OptionResource;

class OptionController extends Controller
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
     * @param  Option  $option
     * @return OptionResource
     */
    public function show(Option $option)
    {
        return new OptionResource($option);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Option  $option
     * @return OptionResource
     */
    public function update(Request $request, Option $option)
    {
        return new OptionResource($option);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        //
    }
}
