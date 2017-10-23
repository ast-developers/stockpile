<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App;

class Locale
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
        if(Session::get('dflt_lang'))
            App::setLocale(Session::get('dflt_lang'));
        else
            App::setLocale('en');
        
        return $next($request);
    }
}
