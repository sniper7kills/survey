<?php

namespace Sniper7Kills\Survey\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SurveyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guest())
            return abort(403);
        if(Auth::user()->id > 1)
            return abort(403);
        return $next($request);
    }
}
