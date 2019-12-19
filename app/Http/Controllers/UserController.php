<?php

namespace App\Http\Controllers;

use App\{Profession, Skill, User, UserProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){

        $users = User::all();

        $title = 'Listado de Usuarios';

        return view('users.index', compact('users','title'));

    }

    public function show(User $user){

        return view('users.show', compact('user'));
    }

    public function create()
    {
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();

        return view('users.create', compact('professions', 'skills'));
    }

    public function store(CreateUserRequest $request){            
        $request->createUser();
            
        return redirect()->route('users.index');
    }
    
    public function edit(User $user){
        return view('users.edit', compact('user'));
    }

    public function update(User $user){

        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => '',
        ]);

        if($data['password'] != null) {
            $data['password'] = bycrypt($data['password']);
        }else{
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route("users.show", ['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
