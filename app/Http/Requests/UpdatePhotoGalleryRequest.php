<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $galleryId = null;
        
        try {
            if ($this->route()->hasParameter('photoGallery')) {
                $gallery = $this->route('photoGallery');
                $galleryId = is_object($gallery) ? $gallery->id : $gallery;
            } elseif ($this->route()->hasParameter('photo_gallery')) {
                $gallery = $this->route('photo_gallery');
                $galleryId = is_object($gallery) ? $gallery->id : $gallery;
            }
        } catch (\Exception $e) {
            $galleryId = null;
        }
        
        return [
            'title' => 'required|string|max:191',
            'slug' => $galleryId 
                ? 'nullable|string|max:191|unique:photo_galleries,slug,' . $galleryId
                : 'nullable|string|max:191',
            'description' => 'nullable|string',
            'cover_image_id' => 'nullable|exists:media,id',
            'visibility' => 'required|in:public,authenticated',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'published_at' => 'nullable|date',
            
            // Photos sélectionnées
            'photos' => 'nullable|array',
            'photos.*' => 'exists:media,id',
            
            // Légendes des photos
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la galerie est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 191 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'cover_image_id.exists' => 'L\'image de couverture sélectionnée n\'existe pas.',
            'visibility.required' => 'La visibilité est obligatoire.',
            'visibility.in' => 'La visibilité doit être "public" ou "authenticated".',
            'photos.*.exists' => 'Une ou plusieurs photos sélectionnées n\'existent pas.',
        ];
    }

    protected function prepareForValidation()
    {
        // Générer automatiquement le slug si vide
        if (empty($this->slug) && !empty($this->title)) {
            $this->merge([
                'slug' => \Str::slug($this->title)
            ]);
        }

        // Convertir les booléens
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => $this->boolean('is_published')
            ]);
        } else {
            $this->merge([
                'is_published' => false
            ]);
        }

        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => $this->boolean('is_featured')
            ]);
        } else {
            $this->merge([
                'is_featured' => false
            ]);
        }
    }
}
