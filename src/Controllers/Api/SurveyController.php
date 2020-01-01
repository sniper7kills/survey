<?php

namespace Sniper7Kills\Survey\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sniper7Kills\Survey\Controllers\BaseController;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Requests\SurveyStoreRequest;
use Sniper7Kills\Survey\Requests\SurveyUpdateRequest;
use Sniper7Kills\Survey\Resources\SurveyResource;

class SurveyController extends BaseController
{
    public function __construct()
    {
        $this->middleware(SurveyAdminMiddleware::class)->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SurveyResource::collection(Survey::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SurveyStoreRequest $request
     * @return SurveyResource
     */
    public function store(SurveyStoreRequest $request)
    {
        $request = $request->validated();

        if(key_exists('available_until',$request))
            $request['available_until'] = Carbon::make($request['available_until']);

        $survey = new Survey($request);
        $survey->save();

        if(key_exists('key',$request))
        {
            if($request['key'] == 'id'){
                $survey->setUrlAsId();
            }elseif($request['key'] == 'slug'){
                $survey->setUrlAsSlug();
            }
        }

        return SurveyResource::make($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  Survey  $survey
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|SurveyResource
     */
    public function show(Survey $survey)
    {
        /**
         * Check if the survey is currently available
         */
        if($survey->available_at > Carbon::now() || $survey->available_at == null)
        {
            if(Auth::guest())
                return response('',403);
            else if(!method_exists(Auth::user(),'isASurveyAdmin'))
                return response('',403);
            else if(!Auth::user()->isASurveyAdmin())
                return response('',403);
        }
        if($survey->available_until != null && $survey->available_until < Carbon::now())
        {
            if(Auth::guest())
                return response('',403);
            else if(!method_exists(Auth::user(),'isASurveyAdmin'))
                return response('',403);
            else if(!Auth::user()->isASurveyAdmin())
                return response('',403);
        }
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SurveyUpdateRequest $request
     * @param  Survey  $survey
     * @return SurveyResource
     */
    public function update(SurveyUpdateRequest $request, Survey $survey)
    {
        $request = $request->validated();

        if(key_exists('available_until',$request))
            $request['available_until'] = Carbon::make($request['available_until']);

        $survey->update($request);

        return SurveyResource::make($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();

        return response('',204);
    }
}
