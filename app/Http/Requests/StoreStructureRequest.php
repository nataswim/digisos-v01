<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'photo'       => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ];
    }
}