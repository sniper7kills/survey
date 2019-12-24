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
            return redirect('/');
        if(Auth::user()->id != 1)
            return redirect('/');

        return $next($request);
    }
}
