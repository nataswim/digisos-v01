<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEspaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'structure_id' => ['required', 'integer', 'exists:structures,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'capacity'     => ['nullable', 'integer', 'min:1'],
            'photo'        => ['nullable', 'string'],
            'is_active'    => ['boolean'],
        ];
    }
}