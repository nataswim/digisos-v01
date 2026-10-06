<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Pages publiques « fixes » du site du club : accueil, pages d'information et formulaire de contact.
 *
 * Les actualités sont gérées par PostController (indexPublic, showPublic, byCategory, byTag),
 * la recherche par SearchController.
 */
class PublicController extends Controller
{
    /**
     * Adresse qui reçoit les messages du formulaire de contact.
     * Peut être remplacée sans toucher au code avec une entrée « contact_to » dans config/mail.php.
     */
    private const CONTACT_RECIPIENT = 'cnbb079@gmail.com';

    /**
     * Sujets proposés dans le formulaire de contact (valeur technique => libellé affiché).
     * La vue public/contact.blade.php utilise les mêmes valeurs.
     */
    public const CONTACT_SUBJECTS = [
        'information' => 'Renseignements sur le club',
        'billing'     => 'Inscription, adhésion, cotisation',
        'support'     => 'Problème sur le site ou sur mon compte',
        'partnership' => 'Partenariat, bénévolat',
        'other'       => 'Autre demande',
    ];

    /**
     * Page d'accueil.
     */
    public function home()
    {
        $recentPosts = Post::with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        return view('public.home', compact('recentPosts'));
    }
/**
 * Revue de presse : les articles publiés dans la catégorie « Presse ».
 */
public function press()
{
    $category = Category::where('slug', 'presse')
        ->where('status', 'active')
        ->first();

    $posts = Post::with('category')
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->where('category_id', $category?->id ?? 0)
        ->orderBy('published_at', 'desc')
        ->paginate(12);

    return view('public.press', compact('category', 'posts'));
}

    public function about()
    {
        return view('public.about');
    }

    public function accessibility()
    {
        return view('public.accessibility');
    }

    public function cookies()
    {
        return view('public.cookies');
    }

    public function features()
    {
        return view('public.features');
    }

    public function legal()
    {
        return view('public.legal');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function pricing()
    {
        return view('public.pricing');
    }

    public function guide()
    {
        return view('public.guide');
    }

    /**
     * Afficher le formulaire de contact.
     */
    public function contact()
    {
        return view('public.contact', ['sujets' => self::CONTACT_SUBJECTS]);
    }

    /**
     * Traiter l'envoi du formulaire de contact.
     */
    public function contactSend(Request $request)
    {
        // Piège à robots : ce champ est invisible pour les visiteurs. S'il est rempli,
        // on répond comme si tout s'était bien passé, sans rien envoyer.
        if ($request->filled('website')) {
            return redirect()->to(route('contact') . '#formulaire')
                ->with('contact_success', 'Votre message a bien été envoyé. Le club vous répondra dès que possible.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'required|in:' . implode(',', array_keys(self::CONTACT_SUBJECTS)),
            'message'    => 'required|string|min:20|max:5000',
        ], [
            'first_name.required' => 'Le prénom est requis.',
            'first_name.max'      => 'Le prénom ne peut pas dépasser 255 caractères.',
            'last_name.required'  => 'Le nom est requis.',
            'last_name.max'       => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required'      => 'L\'adresse e-mail est requise.',
            'email.email'         => 'L\'adresse e-mail n\'est pas valide.',
            'phone.max'           => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'subject.required'    => 'Choisissez un sujet.',
            'subject.in'          => 'Le sujet sélectionné n\'est pas valide.',
            'message.required'    => 'Le message est requis.',
            'message.min'         => 'Le message doit contenir au moins 20 caractères.',
            'message.max'         => 'Le message ne peut pas dépasser 5000 caractères.',
        ]);

        $contactData = array_merge($validated, [
            'subject_label' => self::CONTACT_SUBJECTS[$validated['subject']],
        ]);

        try {
            Mail::to(config('mail.contact_to', self::CONTACT_RECIPIENT))
                ->send(new ContactFormMail($contactData));

            return redirect()->to(route('contact') . '#formulaire')
                ->with('contact_success', 'Votre message a bien été envoyé. Le club vous répondra dès que possible.');
        } catch (\Throwable $e) {
            Log::error('Erreur envoi e-mail contact : ' . $e->getMessage());

            return redirect()->to(route('contact') . '#formulaire')
                ->withInput()
                ->with('contact_error', 'Le message n\'a pas pu être envoyé. Réessayez dans un moment, ou écrivez-nous directement à cnbb079@gmail.com.');
        }
    }
}
