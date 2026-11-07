<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace adhérent → « Mes messages » : uniquement les conversations dont l'utilisateur est participant.
 * Il peut écrire à un coach précis (ou à l'administrateur) et répondre aux messages reçus.
 * Un compte « visiteur » (non adhérent) peut seulement lire et répondre aux messages reçus du club.
 */
class MemberMessageController extends Controller
{
    public function __construct(private MessageService $messages)
    {
    }

    public function index(Request $request): View
    {
        $me = $request->user();

        return view('messages.member.index', [
            'conversations' => Conversation::with(['staff', 'user'])->involving($me)->orderByDesc('last_message_at')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        abort_if($request->user()->hasRole('visitor'), 403, 'La messagerie est réservée aux adhérents du club.');

        return view('messages.member.create', ['staff' => $this->recipients(), 'selected' => $request->integer('a')]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->hasRole('visitor'), 403, 'La messagerie est réservée aux adhérents du club.');

        $ids = $this->recipients()->pluck('id')->all();
        $data = $request->validate([
            'staff_id' => ['required', 'integer', Rule::in($ids)],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:10000'],
            'attachment' => MessageService::ATTACHMENT_RULES,
        ], [
            'staff_id.required' => 'Choisissez le coach à qui écrire.',
            'staff_id.in' => 'Choisissez le coach à qui écrire.',
            'attachment.mimes' => 'La pièce jointe doit être un PDF ou une image (JPG, PNG, WebP).',
            'attachment.max' => 'La pièce jointe ne doit pas dépasser 5 Mo.',
        ]);

        $c = $this->messages->startFromMember($request->user(), User::findOrFail($data['staff_id']), trim($data['subject']), trim($data['body']), $request->file('attachment'));

        return redirect()->route('user.messages.show', $c)->with('success', 'Message envoyé.');
    }

    public function show(Request $request, Conversation $conversation): View
    {
        abort_unless($conversation->isParticipant($request->user()), 404);
        $conversation->markReadBy($request->user());
        $conversation->load(['messages.author.role', 'staff.role', 'user']);

        return view('messages.member.show', compact('conversation'));
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        abort_unless($conversation->isParticipant($request->user()), 404);
        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'attachment' => MessageService::ATTACHMENT_RULES,
        ], ['attachment.mimes' => 'La pièce jointe doit être un PDF ou une image.', 'attachment.max' => 'La pièce jointe ne doit pas dépasser 5 Mo.']);

        $this->messages->reply($conversation, $request->user(), trim($data['body']), $request->file('attachment'));

        return redirect()->to(route('user.messages.show', $conversation) . '#dernier')->with('success', 'Réponse envoyée.');
    }

    /** Coachs et administrateurs actifs, à qui un adhérent peut écrire. */
    private function recipients()
    {
        return User::with('role')->where('status', 'active')->where('id', '!=', request()->user()->id)
            ->whereHas('role', fn ($q) => $q->whereIn('slug', Conversation::MEMBER_CAN_WRITE_TO))
            ->orderBy('first_name')->orderBy('name')->get();
    }
}
