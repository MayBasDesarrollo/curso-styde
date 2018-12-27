<?php

namespace App\Http\Controllers;

use App\{User, UserProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function index(){

        $users = User::all();

        //dd($users);

        $title = 'Listado de Usuarios';

        return view('users.index', compact('users','title'));

    }

    //public function show($id){
    public function show(User $user){

        /*$users = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->get();
        */

        //$user = User::findOrFail($id);
        // $user = User::find($id);

        // if($user == null){
        //     return response()->view('errors.404', [], 404);
        // }

        return view('users.show', compact('user'));
    }

    public function create(){
        return view('users.create');
    }

    public function store(CreateUserRequest $request){            
        $request->createUser();
            
        return redirect()->route('users.index');
    }
    
    public function edit(User $user){
        //return "El usuario $id a sido modificado correctamente";
        return view('users.edit', compact('user'));
    }

    public function update(User $user){

        $data = request()->all();
        $data['password'] = bcrypt($data['password']);

        $user->update($data);

        //return redirect("/usuarios/{$user->id}");
        return redirect()->route("users.show", ['user' => $user]);
    }
}
