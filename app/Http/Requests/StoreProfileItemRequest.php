<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileItemRequest extends FormRequest
{
    /**
     * Seul l'administrateur peut créer un élément de contenu.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Règles de validation pour la création d'un ProfileItem.
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    /**
     * Messages de validation personnalisés en français.
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'Le titre est obligatoire.',
            'title.max'            => 'Le titre ne peut pas dépasser 200 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.max'      => 'La description ne peut pas dépasser 5 000 caractères.',
            'sort_order.integer'   => "L'ordre d'affichage doit être un nombre entier.",
            'sort_order.min'       => "L'ordre d'affichage ne peut pas être négatif.",
        ];
    }

    /**
     * Prépare les données avant validation :
     * affecte automatiquement sort_order à 0 s'il est absent.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('sort_order') || $this->sort_order === null) {
            $this->merge(['sort_order' => 0]);
        }
    }
}
