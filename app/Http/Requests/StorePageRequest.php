<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 🇬🇧 StorePageRequest - Validation for creating a page
 * 🇫🇷 StorePageRequest - Validation pour créer une page
 * 
 * @file app/Http/Requests/StorePageRequest.php
 */
class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191|unique:pages,slug',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'image' => 'nullable|string|max:2048',
            'visibility' => 'required|string|in:public,authenticated',
            'is_published' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'pages_category_id' => 'nullable|exists:pages_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_og_image' => 'nullable|string|max:2048',
            'meta_og_url' => 'nullable|string|max:2048',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la page est obligatoire.',
            'short_description.required' => 'La description courte est obligatoire.',
            'visibility.required' => 'La visibilité est obligatoire.',
            'visibility.in' => 'La visibilité doit être "public" ou "authenticated".',
            'pages_category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => $this->boolean('is_published')
            ]);
        }

        if (!$this->has('visibility') || empty($this->visibility)) {
            $this->merge([
                'visibility' => 'public'
            ]);
        }
    }
}
