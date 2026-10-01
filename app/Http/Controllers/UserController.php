<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use App\Role;
use DB;
use App\Models\Login;

class UserController extends Controller
{
    
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $records = User::All();
        return View('users.index',compact('records'));
    }

    /**
     * Vista para crear usuario
     */
    public function create()
    {
        return View('users.create');
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        
        $request->merge(['password' => bcrypt($request->input('password'))]);

        $user = User::create($request->all());

        $user->roles()->attach(Role::where('id', $request->input('role_id'))->first());

        return redirect()->route('users.index')
                        ->with('success','Usuario creado correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $user = User::find($id);
        if($user->hasRole('admin')){
            $role_id = 1;
        }else if($user->hasRole('gerencial')){
            $role_id = 2;
        }else if($user->hasRole('client')){
            $role_id = 3;
        }
        return view('users.edit',compact('user','role_id'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        if(!empty($request->input('password'))){
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $data = $request->all();
        }else{
            $data = $request->except(['password']);
        }


        User::find($id)->update($data);

        $user = User::find($id);

        DB::table('role_user')->where('user_id',$id)->delete();

        $user->roles()->attach(Role::where('id', $request->input('role_id'))->first());

        return redirect()->route('users.index')
                        ->with('success','Usuario editado corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(User $user)
    {
        if($user->id != 1){
            User::find($user->id)->delete();

            DB::table('role_user')->where('user_id',$user->id)->delete();

            return redirect()->route('users.index')
                        ->with('success','Usuario eliminado correctamente.');
        }else{
            return redirect()->route('users.index')
                        ->with('alert','El administrador principal no se puede eliminar.');
        }
        
    }

    public function logins(){
        $logins = Login::orderBy('created_at','ASC')->get();
        return View('users.logins',compact('logins'));
    }

}
