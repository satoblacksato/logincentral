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
            'pass_key'=>'required',
            'token'=>'required|exists:'.config('logincentral.connect_database').'.manager_access,token'
        ]);

        if(($request->pass_key)!=config('logincentral.pass_key')){
            abort(404,'Pass_KEY INCORRECTO');
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
        $userSystem=User
            ::where($column,'=',$data->$column)->first();
        if($userSystem==null){
            abort(404,'No existe usuario asociado al sistema');
        }

        Auth::logout();
        Auth::login($userSystem,true);
        if (Auth::check()) {
            return    redirect()->intended('/home');
        }
        abort(401,'No tienes autorización para ingresar al sistema');

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