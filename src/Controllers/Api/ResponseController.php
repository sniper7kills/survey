<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Illuminate\Http\Request;
use Sniper7Kills\Survey\Controllers\Controller;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Resources\ResponseResource;

class ResponseController extends Controller
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
     * @param  Response  $response
     * @return ResponseResource
     */
    public function show(Response $response)
    {
        return new ResponseResource($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Response  $response
     * @return ResponseResource
     */
    public function update(Request $request, Response $response)
    {
        return new ResponseResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }
}
