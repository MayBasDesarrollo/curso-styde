<?php

namespace App\Http\Controllers;

use App\{Profession, Skill, User, UserProfile};
use App\Http\Forms\UserForm;
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
        $user = new User;

        return new UserForm('users.create', new User);
    }

    public function store(CreateUserRequest $request){            
        $request->createUser();
            
        return redirect()->route('users.index');
    }
    
    public function edit(User $user)
    {
        return new UserForm('users.edit', $user);
    }

    public function update(User $user){

        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => '',
            'role' => '',
            'bio' => '',
            'profession_id' => '',
            'twitter' => '',
            'skills'=> '',
        ]);

        if($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        $user->fill($data);
        $user->role = $data['role'];
        $user->save();

        $user->profile->update($data);

        $user->skills()->sync($data['skills'] ?? []);

        return redirect()->route("users.show", ['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
