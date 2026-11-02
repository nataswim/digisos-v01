<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\MemberCategory;
use App\Models\Membership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/** Catégories et tarifs des adhésions. Le tarif s'applique aux nouvelles adhésions (les adhésions existantes gardent le leur). */
class MemberCategoryController extends MembersArea
{
    public function index(): View
    {
        return $this->membersView('categories', [
            'categories' => MemberCategory::ordered()->withCount(['memberships' => fn ($q) => $q])->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MemberCategory::create($this->validated($request));

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function update(Request $request, MemberCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return back()->with('success', "Catégorie {$category->code} mise à jour.");
    }

    public function destroy(MemberCategory $category): RedirectResponse
    {
        if (Membership::where('member_category_id', $category->id)->exists()) {
            $category->update(['is_active' => false]);

            return back()->with('success', "La catégorie {$category->code} est utilisée : elle est désactivée au lieu d'être supprimée.");
        }
        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    private function validated(Request $request, ?MemberCategory $category = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('member_categories', 'code')->ignore($category?->id)],
            'label' => ['required', 'string', 'max:120'],
            'price' => ['required', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $prix = Membership::parseMoney($data['price']);
        if ($prix === null) {
            throw ValidationException::withMessages(['price' => 'Tarif illisible (exemple : 190 ou 190,00).']);
        }

        return [
            'code' => mb_strtoupper(trim($data['code'])),
            'label' => $data['label'],
            'price_cents' => $prix,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];
    }
}
