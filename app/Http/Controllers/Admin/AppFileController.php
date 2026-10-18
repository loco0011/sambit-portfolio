<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Support\AppDownloads;
use App\Support\PortfolioContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** The "After hours" projects in the admin panel: their release APKs and how their pages are doing. */
class AppFileController extends Controller
{
    public function index(): JsonResponse
    {
        $since = now()->subDays(30);
        $count = fn (string $path, bool $prefix = false) => [
            'total' => PageView::where('path', $prefix ? 'like' : '=', $path)->count(),
            'last_30' => PageView::where('path', $prefix ? 'like' : '=', $path)->where('created_at', '>=', $since)->count(),
        ];

        $apps = collect(PortfolioContent::get()['side_projects'] ?? [])
            ->map(fn ($app) => [
                'slug' => $app['slug'],
                'name' => $app['name'],
                'tag' => $app['tag'] ?? null,
                'status' => $app['status'] ?? null,
                'platform' => $app['platform'] ?? 'android',
                'website' => $app['website'] ?? null,
                'icon' => $app['icon'] ?? null,
                'page' => route('apps.show', $app['slug'], false),
                'views' => $count("/apps/{$app['slug']}"),
                'downloads' => $count("/apps/{$app['slug']}/download/%", true),
                'builds' => array_map(fn ($b) => $b + [
                    'url' => route('apps.download', [$app['slug'], $b['key']], false),
                    'downloads' => $count("/apps/{$app['slug']}/download/{$b['key']}"),
                ], AppDownloads::builds($app)),
            ])
            ->values();

        return response()->json(['apps' => $apps]);
    }

    public function store(Request $request, string $slug, string $key): JsonResponse
    {
        $app = AppDownloads::app($slug);
        $build = collect($app['downloads'] ?? [])->firstWhere('key', $key);
        abort_unless($app && $build && empty($build['external']), 404);

        $request->validate(['file' => ['required', 'file', 'extensions:apk', 'max:204800']]);

        AppDownloads::store($request->file('file'), $slug, $key);

        return $this->index();
    }
}
