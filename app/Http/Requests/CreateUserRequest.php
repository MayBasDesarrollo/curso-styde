<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
            'twitter' => ['nullable','url'] 
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
                'twitter'=> array_get($data,'twitter'),
                //'twitter'=>$data['twitter'] ?? null,
            ]);
        });
    }
}
