<?php
/**
 * Created by PhpStorm.
 * User: ernes
 * Date: 20/1/2019
 * Time: 23:35
 */
namespace Eliberio\LoginCentral\Controllers;

use App\Http\Controllers\Controller;
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
           'token'=>'required|exists:'.config('logincentral.connect_database').'.manager_access.token,token'
        ]);

        $data=DB::connection(config('logincentral.connect_database'))
                ->table('users')
                ->where('id',DB::connection(config('logincentral.connect_database'))
                    ->table('manager_access')->where('token','=',$request->get('token'))->first())
                ->first();
        if($data==null){
            return response()->json(['No existe un Token de Validación Asignado'],404);
        }
        Auth::logout();
        Auth::loginUsingId($data->id);
        if(Auth::check()){
            return response()->json("acceso correcto");
        }
        return response()->json(['No tienes autorización para ingresar al sistema'],401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        if(!Auth::check()){
            return response()->json("cierre de sesión es correcto");
        }
        return response()->json(['no puedes cerrar sesión en el sistema'],500);
    }
}