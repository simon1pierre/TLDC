<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\UserActivityLog;
use App\Http\Controllers\Concerns\HandlesTranslations;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use HandlesTranslations;

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $query = Banner::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status') === 'active');
        }

        if ($request->string('deleted') === 'with') {
            $query->withTrashed();
        } elseif ($request->string('deleted') === 'only') {
            $query->onlyTrashed();
        }

        $banners = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        return view('Admin.Banners.index', compact('banners'));
    }

    public function create()
    {
        return view('Admin.Banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'content_en' => ['nullable', 'string'],
            'content_fr' => ['nullable', 'string'],
            'content_rw' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:500'],
            'background_color' => ['nullable', 'string', 'max:20'],
            'text_color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $banner = Banner::create([
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'background_color' => $validated['background_color'] ?? null,
            'text_color' => $validated['text_color'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'banner_created',
            'meta' => [
                'id' => $banner->id,
                'content' => str($banner->content)->limit(60),
            ],
        ]);

        $this->syncTranslationsMapped($banner, $request, [
            'content' => 'description',
        ]);

        return redirect()->route('admin.banners.index')->with('status', 'Banner created.');
    }

    public function edit(Banner $banner)
    {
        return view('Admin.Banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'content_en' => ['nullable', 'string'],
            'content_fr' => ['nullable', 'string'],
            'content_rw' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:500'],
            'background_color' => ['nullable', 'string', 'max:20'],
            'text_color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $banner->update([
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'background_color' => $validated['background_color'] ?? null,
            'text_color' => $validated['text_color'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'banner_updated',
            'meta' => [
                'id' => $banner->id,
                'content' => str($banner->content)->limit(60),
            ],
        ]);

        $this->syncTranslationsMapped($banner, $request, [
            'content' => 'description',
        ]);

        return redirect()->route('admin.banners.index')->with('status', 'Banner updated.');
    }

    public function destroy(Request $request, Banner $banner)
    {
        $banner->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'banner_deleted',
            'meta' => [
                'id' => $banner->id,
                'content' => str($banner->content)->limit(60),
            ],
        ]);

        return redirect()->back()->with('status', 'Banner deleted.');
    }

    public function restore(Request $request, int $banner)
    {
        $record = Banner::withTrashed()->findOrFail($banner);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'banner_restored',
            'meta' => [
                'id' => $record->id,
                'content' => str($record->content)->limit(60),
            ],
        ]);

        return redirect()->back()->with('status', 'Banner restored.');
    }

    public function forceDelete(Request $request, int $banner)
    {
        $record = Banner::withTrashed()->findOrFail($banner);
        $content = str($record->content)->limit(60);
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'banner_force_deleted',
            'meta' => [
                'id' => $banner,
                'content' => (string) $content,
            ],
        ]);

        return redirect()->back()->with('status', 'Banner permanently deleted.');
    }
}
