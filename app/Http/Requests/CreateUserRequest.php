<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:6|max:10',
            'role' => ['nullable', Rule::in(Role::getList())],
            'bio' => 'required',
            'twitter' => ['nullable','url', 'present'] ,
            //'profession_id' => 'exists:professions,id',
            'profession_id' => [
                'nullable', 'present',
                Rule::exists('professions', 'id')->whereNull('deleted_at'),
            ],
            'skills' => [
                'array',
                Rule::exists('skills', 'id'),
            ],
        ];
    }

    /*public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio'
        ];
    }*/
    
    public function createUser()
    {
        
        DB::transaction(function () {
            $data = $this->validated();

            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user->role = $data['role'] ?? 'user';

            $user->save();
            
            $user->profile()->create([
                'bio'=>$data['bio'],
                'twitter'=>$data['twitter'],
                //'twitter'=> array_get($data,'twitter'),
                'profession_id' => $data['profession_id'],
            ]);

            $user->skills()->attach($data['skills'] ?? []);
        });
    }
}
