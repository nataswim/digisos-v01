<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerSlide;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::withCount('slides')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:191',
            'slug'            => 'nullable|string|max:191|unique:banners,slug',
            'height'          => 'required|integer|min:100|max:1200',
            'autoplay'        => 'boolean',
            'autoplay_delay'  => 'required|integer|min:1000|max:30000',
            'show_indicators' => 'boolean',
            'show_controls'   => 'boolean',
            'pause_on_hover'  => 'boolean',
            'transition'      => 'required|in:slide,fade',
            'is_active'       => 'boolean',
            'description'     => 'nullable|string',
        ]);

        $validated['slug']            = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['created_by']      = auth()->id();
        $validated['autoplay']        = $request->boolean('autoplay');
        $validated['show_indicators'] = $request->boolean('show_indicators');
        $validated['show_controls']   = $request->boolean('show_controls');
        $validated['pause_on_hover']  = $request->boolean('pause_on_hover');
        $validated['is_active']       = $request->boolean('is_active');

        $banner = Banner::create($validated);

        return redirect()
            ->route('admin.banners.edit', $banner)
            ->with('success', 'Bannière créée. Ajoutez maintenant vos slides.');
    }

    public function show(Banner $banner): View
    {
        $banner->load('slides');

        return view('admin.banners.show', compact('banner'));
    }

    public function edit(Banner $banner): View
    {
        $banner->load('slides');

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:191',
            'slug'            => 'nullable|string|max:191|unique:banners,slug,' . $banner->id,
            'height'          => 'required|integer|min:100|max:1200',
            'autoplay'        => 'boolean',
            'autoplay_delay'  => 'required|integer|min:1000|max:30000',
            'show_indicators' => 'boolean',
            'show_controls'   => 'boolean',
            'pause_on_hover'  => 'boolean',
            'transition'      => 'required|in:slide,fade',
            'is_active'       => 'boolean',
            'description'     => 'nullable|string',
        ]);

        $validated['slug']            = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['updated_by']      = auth()->id();
        $validated['autoplay']        = $request->boolean('autoplay');
        $validated['show_indicators'] = $request->boolean('show_indicators');
        $validated['show_controls']   = $request->boolean('show_controls');
        $validated['pause_on_hover']  = $request->boolean('pause_on_hover');
        $validated['is_active']       = $request->boolean('is_active');

        $banner->update($validated);

        return redirect()
            ->route('admin.banners.edit', $banner)
            ->with('success', 'Bannière mise à jour.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Bannière supprimée.');
    }

    // =========================================================
    //  Slides
    // =========================================================

    public function storeSlide(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'image'           => 'nullable|string|max:500',
            'image_alt'       => 'nullable|string|max:255',
            'title'           => 'nullable|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'body'            => 'nullable|string',
            'cta_label'       => 'nullable|string|max:100',
            'cta_url'         => 'nullable|string|max:500',
            'cta_target'      => 'nullable|in:_self,_blank',
            'cta_style'       => 'nullable|string|max:50',
            'text_color'      => 'nullable|string|max:20',
            'text_position'   => 'nullable|in:left,center,right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'is_active'       => 'boolean',
        ]);

        $validated['banner_id']   = $banner->id;
        $validated['sort_order']  = $banner->slides()->max('sort_order') + 1;
        $validated['created_by']  = auth()->id();
        $validated['is_active']   = $request->boolean('is_active', true);

        $banner->slides()->create($validated);

        $banner->clearCache();

        return redirect()
            ->route('admin.banners.edit', $banner)
            ->with('success', 'Slide ajouté.');
    }

    public function updateSlide(Request $request, Banner $banner, BannerSlide $slide): RedirectResponse
    {
        abort_unless($slide->banner_id === $banner->id, 403);

        $validated = $request->validate([
            'image'           => 'nullable|string|max:500',
            'image_alt'       => 'nullable|string|max:255',
            'title'           => 'nullable|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'body'            => 'nullable|string',
            'cta_label'       => 'nullable|string|max:100',
            'cta_url'         => 'nullable|string|max:500',
            'cta_target'      => 'nullable|in:_self,_blank',
            'cta_style'       => 'nullable|string|max:50',
            'text_color'      => 'nullable|string|max:20',
            'text_position'   => 'nullable|in:left,center,right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'is_active'       => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id();
        $validated['is_active']  = $request->boolean('is_active', true);

        $slide->update($validated);

        $banner->clearCache();

        return redirect()
            ->route('admin.banners.edit', $banner)
            ->with('success', 'Slide mis à jour.');
    }

    public function destroySlide(Banner $banner, BannerSlide $slide): RedirectResponse
    {
        abort_unless($slide->banner_id === $banner->id, 403);

        $slide->delete();
        $banner->clearCache();

        return redirect()
            ->route('admin.banners.edit', $banner)
            ->with('success', 'Slide supprimé.');
    }

    public function reorderSlides(Request $request, Banner $banner): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:banner_slides,id',
        ]);

        foreach ($request->order as $position => $slideId) {
            BannerSlide::where('id', $slideId)
                       ->where('banner_id', $banner->id)
                       ->update(['sort_order' => $position]);
        }

        $banner->clearCache();

        return response()->json(['success' => true]);
    }
}
