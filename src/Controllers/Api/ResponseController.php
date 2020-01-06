<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Controllers\BaseController;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;
use Sniper7Kills\Survey\Requests\ResponseStoreRequest;
use Sniper7Kills\Survey\Resources\ResponseResource;

class ResponseController extends BaseController
{
    public function __construct()
    {
        $this->middleware(SurveyAdminMiddleware::class)->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ResponseResource::collection(Response::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ResponseStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|ResponseResource
     */
    public function store(ResponseStoreRequest $request)
    {
        $request = $request->validated();

        $survey = Survey::find($request['survey_id']);

        if($survey->available_at > Carbon::now() || $survey->available_at == null)
        {
                return response('',403);
        }else if($survey->available_until != null && $survey->available_until < Carbon::now())
        {
                return response('',403);
        }

        if(Auth::guest()){
            if(!$survey->guests)
                return response('',403);
            $user = SurveyGuest::current();
        }else
            $user = Auth::user();

        $response = new Response();
        $response->userable()->associate($user);

        $survey->responses()->save($response);

        return ResponseResource::make($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Response $response
     * @return ResponseResource
     */
    public function show(Response $response)
    {
        return new ResponseResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Response $response
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Response $response)
    {
        $response->delete();

        return response('',204);
    }
}
