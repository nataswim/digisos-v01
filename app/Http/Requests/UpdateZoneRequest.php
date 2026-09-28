<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'espace_id'   => ['required', 'integer', 'exists:espaces,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'photo'       => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ];
    }
}