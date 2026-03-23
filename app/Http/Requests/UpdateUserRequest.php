<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'      => ['nullable', 'string', 'max:255', 'unique:users,username,' . $this->user->id],
            'first_name'    => ['nullable', 'string', 'max:255'],
            'last_name'     => ['nullable', 'string', 'max:255'],
            'name'          => ['nullable', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'       => ['required', 'exists:roles,id'],

            // URL issue de la médiathèque — chaîne vide acceptée (suppression)
            'avatar'        => ['nullable', 'string', 'max:500'],

            'bio'           => ['nullable', 'string'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'status'        => ['required', 'string', 'in:active,inactive'],
            'locale'        => ['nullable', 'string', 'max:10'],
            'timezone'      => ['nullable', 'string', 'max:50'],
        ];
    }
}