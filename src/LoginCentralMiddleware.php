<?php
/**
 * Created by PhpStorm.
 * User: ernes
 * Date: 21/1/2019
 * Time: 19:29
 */

namespace Eliberio\LoginCentral;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use DB;
use Auth;
class LoginCentralMiddleware
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
        if(Auth::check()){
        $data=DB::connection(config('logincentral.connect_database'))
            ->table('users')
            ->where('email',Auth::user()->email)
            ->first();

        if($data==null){
            abort(403,'Usuario no autorizado debe estar en el panel principal');
        }

        if(DB::connection(config('logincentral.connect_database'))
            ->table('manager_access')
            ->where('user_id',$data->id)
            ->count()>0){
            return $next($request);
        }
        Auth::logout();
        $request->session()->invalidate();
        return redirect(config('logincentral.main_url'));
        }else{
            return $next($request);
        }
    }
}
