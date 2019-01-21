<?php
/**
 * Created by PhpStorm.
 * User: ernes
 * Date: 20/1/2019
 * Time: 23:35
 */
namespace Eliberio\LoginCentral\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use PHPUnit\Util\ConfigurationGenerator;
use DB;
use Auth;
class ProcessLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

       $this->validate($request,[
            'user_key'=>'required|in:'.config('logincentral.user_key'),
            'pass_key'=>'required|in:'.config('logincentral.pass_key'),
            'token'=>'required|exists:'.config('logincentral.connect_database').'.manager_access,token'
        ]);

        if(Auth::guard('web')->check()){
            Auth::logout();
        } 
       
        $data=DB::connection(config('logincentral.connect_database'))
            ->table('users')
            ->where('id',optional(DB::connection(config('logincentral.connect_database'))
                ->table('manager_access')->where('token','=',($request->get('token')))->first())->user_id)
            ->first();

        if($data==null){
            abort(404,'No existe un Token de Validación Asignado');
        }

        $column=config('logincentral.column_merge');
        $user = config('auth.providers.users.model')::where($column,'=',$data->$column)->first();
        if($user==null){
            abort(404,'No existe usuario asociado al sistema');
        }

        Auth::guard('web')->login($user, true);
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $this->validate($request,[
            'user_key'=>'required|in:'.config('logincentral.user_key'),
            'pass_key'=>'required|in:'.config('logincentral.pass_key')
        ]);


        Auth::logout();
        return response()->json(['proceso de logout exitoso'],200);
    }
}
