<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'required', 'email', 
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => '',
            'role' => [Rule::in(Role::getList())], //No hace falta el requiered porque la regla rule lo abliga a tener un rol
            'bio' => 'required',
            'twitter' => ['nullable','url', 'present'], //falta el present
            'profession_id' => [
                'nullable', 'present',
                Rule::exists('professions', 'id')->whereNull('deleted_at'),
            ], //falta el presente



            // FALTA REALIXAR LAS PRUEBAS DE TODAS ESTAS VALIDACIONES EN EL UpdateUserRequest
            'skills' => [
                // 'array',
                Rule::exists('skills', 'id'),
            ],
        ];
    }

    public function updateUser(User $user)
    {
        $data = $this->validated();
        
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
    }
}
