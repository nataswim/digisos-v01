<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /** Lien avec le club, proposé à l'inscription : [libellé, précision, icône] */
    public const PROFILES = [
        'nageur' => ['Adhérent nageur', 'Nageur licencié au club', 'fa-person-swimming'],
        'ecole' => ['École de natation', "Parent d'un enfant de l'école de natation", 'fa-children'],
        'maitres' => ['Maîtres', 'Nageur adulte de la section Maîtres', 'fa-medal'],
        'partenaire' => ['Partenaire', 'Entreprise ou collectivité partenaire du club', 'fa-handshake'],
        'benevole' => ['Bénévole', 'Officiel, dirigeant, accompagnateur…', 'fa-hands-helping'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Étape 1 : Informations de compte
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Étape 2 : Informations personnelles
            'username' => ['required', 'string', 'max:60', 'unique:users,username', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:-17 years'],

            // Étape 3 : Coordonnées et profil
            'phone' => ['required', 'string', 'regex:/^(\+33|0)[1-9](\d{2}){4}$/'],
            'profiles' => ['required', 'array', 'min:1'],
            'profiles.*' => ['string', \Illuminate\Validation\Rule::in(array_keys(self::PROFILES))],
        ];
    }

    /** « Adhérent nageur | Bénévole » : enregistré dans le champ bio du compte. */
    public function profileSummary(): string
    {
        return collect($this->input('profiles', []))
            ->map(fn ($key) => self::PROFILES[$key][0] ?? null)
            ->filter()->unique()->implode(' | ');
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, tirets et underscores.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'date_of_birth.before' => 'Vous devez avoir au moins 17 ans pour vous inscrire.',
            'phone.regex' => 'Le numéro de téléphone doit être au format français valide (ex: 0612345678 ou +33612345678).',
            'profiles.required' => 'Indiquez votre lien avec le club (au moins une case).',
            'profiles.min' => 'Indiquez votre lien avec le club (au moins une case).',
            'profiles.*.in' => 'Choix de profil invalide.',
        ];
    }

    /**
     * Attributs personnalisés pour les messages
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom complet',
            'email' => 'adresse email',
            'password' => 'mot de passe',
            'username' => 'nom d\'utilisateur',
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'date_of_birth' => 'date de naissance',
            'phone' => 'téléphone',
            'profiles' => 'lien avec le club',
        ];
    }
}
