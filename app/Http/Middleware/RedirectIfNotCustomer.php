<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use DB;
class RedirectIfNotCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'customer')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/customer');
        }else{
            $customer_id = Auth::guard('customer')->user()->debtor_no;
            $customer = DB::table('debtors_master')->where('debtor_no',$customer_id)->first();
            //d($customer,1);
            if($customer->inactive == 1){
                Auth::guard($guard)->logout();
                return redirect('/customer');
            }            
        }
        return $next($request);
    }
}
