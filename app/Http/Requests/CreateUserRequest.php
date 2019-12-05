<?php

namespace App\Http\Requests;

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
            'bio' => 'required',
            'twitter' => ['nullable','url', 'present'] ,
            //'profession_id' => 'exists:professions,id',
            'profession_id' => [
                'nullable', 'present',
                Rule::exists('professions', 'id')->whereNull('deleted_at'),
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

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            
            $user->profile()->create([
                'bio'=>$data['bio'],
                'twitter'=>$data['twitter'],
                //'twitter'=> array_get($data,'twitter'),
                'profession_id' => $data['profession_id'],
            ]);
        });
    }
}
