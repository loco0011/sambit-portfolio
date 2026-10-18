<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AppDownloads;
use App\Support\PortfolioContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** APK uploads for the "After hours" app pages. */
class AppFileController extends Controller
{
    public function index(): JsonResponse
    {
        $apps = collect(PortfolioContent::get()['side_projects'] ?? [])
            ->filter(fn ($app) => ! empty($app['downloads']))
            ->map(fn ($app) => [
                'slug' => $app['slug'],
                'name' => $app['name'],
                'icon' => $app['icon'] ?? null,
                'page' => route('apps.show', $app['slug'], false),
                'builds' => array_map(fn ($b) => $b + ['url' => route('apps.download', [$app['slug'], $b['key']], false)], AppDownloads::builds($app)),
            ])
            ->values();

        return response()->json(['apps' => $apps]);
    }

    public function store(Request $request, string $slug, string $key): JsonResponse
    {
        $app = AppDownloads::app($slug);
        abort_unless($app && collect($app['downloads'] ?? [])->contains('key', $key), 404);

        $request->validate(['file' => ['required', 'file', 'extensions:apk', 'max:204800']]);

        AppDownloads::store($request->file('file'), $slug, $key);

        return $this->index();
    }
}
