<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CoachDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Espace coachs → Documents réservés aux coachs.
 * Les fichiers sont stockés hors du dossier public et ne sont téléchargeables que depuis cet espace.
 */
class CoachDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('categorie');
        $search = trim((string) $request->query('q', ''));

        $documents = CoachDocument::with('uploader')
            ->when($category && isset(CoachDocument::CATEGORIES[$category]), fn ($q) => $q->where('category', $category))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('coach.documents.index', compact('documents', 'category', 'search'));
    }

    public function create(): View
    {
        return view('coach.documents.form', ['document' => new CoachDocument()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);
        $data['uploaded_by'] = $request->user()->id;

        $document = new CoachDocument($data);
        $this->storeFile($request, $document);
        $document->save();

        return redirect()->route('coach.documents.index')->with('success', "Document « {$document->title} » ajouté.");
    }

    public function edit(CoachDocument $document): View
    {
        return view('coach.documents.form', compact('document'));
    }

    public function update(Request $request, CoachDocument $document): RedirectResponse
    {
        $document->fill($this->validated($request, $document));

        if ($request->boolean('remove_file') && ! $request->hasFile('file')) {
            $document->deleteFile();
            $document->fill(['file_path' => null, 'original_name' => null, 'mime_type' => null, 'size' => null]);
        }
        $this->storeFile($request, $document);

        if (! $document->hasFile() && blank($document->url)) {
            return back()->withInput()->withErrors(['file' => 'Ajoutez un fichier ou un lien.']);
        }

        $document->save();

        return redirect()->route('coach.documents.index')->with('success', "Document « {$document->title} » mis à jour.");
    }

    public function destroy(CoachDocument $document): RedirectResponse
    {
        $title = $document->title;
        $document->delete();

        return redirect()->route('coach.documents.index')->with('success', "Document « {$title} » supprimé.");
    }

    public function download(CoachDocument $document): StreamedResponse|RedirectResponse
    {
        if (! $document->hasFile()) {
            return $document->url ? redirect()->away($document->url) : redirect()->route('coach.documents.index');
        }

        abort_unless(Storage::disk(CoachDocument::DISK)->exists($document->file_path), 404);

        return Storage::disk(CoachDocument::DISK)->download($document->file_path, $document->original_name);
    }

    private function validated(Request $request, ?CoachDocument $document): array
    {
        $hasFile = $document?->hasFile() && ! $request->boolean('remove_file');

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', Rule::in(array_keys(CoachDocument::CATEGORIES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'url' => ['nullable', 'url:http,https', 'max:500', Rule::requiredIf(fn () => ! $request->hasFile('file') && ! $hasFile)],
            'file' => ['nullable', 'file', 'mimes:' . implode(',', CoachDocument::EXTENSIONS), 'max:' . CoachDocument::MAX_KB],
        ], [
            'url.required' => 'Ajoutez un fichier ou un lien.',
            'file.mimes' => 'Type de fichier non accepté (PDF, Word, Excel, PowerPoint, OpenDocument, texte ou image).',
            'file.max' => 'Le fichier dépasse 20 Mo.',
        ]);
    }

    private function storeFile(Request $request, CoachDocument $document): void
    {
        if (! $request->hasFile('file')) {
            return;
        }

        $file = $request->file('file');
        $document->deleteFile();
        $document->fill([
            'file_path' => $file->store(CoachDocument::DIRECTORY, CoachDocument::DISK),
            'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
