<?php

namespace App\Http\Controllers;

use App\Support\AppDownloads;
use App\Support\PortfolioContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Public page for each "After hours" app, and its APK downloads. */
class AppPageController extends Controller
{
    public function show(string $slug): View
    {
        $portfolio = PortfolioContent::get();
        $app = AppDownloads::app($slug, $portfolio);
        abort_unless($app, 404);

        $url = route('apps.show', $slug);
        $home = url('/');

        return view('app-page', [
            'portfolio' => $portfolio,
            'app' => $app,
            'builds' => AppDownloads::builds($app),
            'others' => collect($portfolio['side_projects'])->reject(fn ($a) => $a['slug'] === $slug)->values()->all(),
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => $portfolio['profile']['name'], 'item' => $home],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'After hours', 'item' => $home.'/#after-hours'],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $app['name'], 'item' => $url],
                        ],
                    ],
                    [
                        '@type' => ($app['platform'] ?? 'android') === 'web' ? 'WebApplication' : 'MobileApplication',
                        '@id' => $url.'#app',
                        'name' => $app['name'],
                        'description' => $app['kind'],
                        'url' => $url,
                        'applicationCategory' => $app['category'] ?? 'UtilitiesApplication',
                        'operatingSystem' => ($app['platform'] ?? 'android') === 'web' ? 'Web' : 'Android',
                        'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'INR'],
                        'author' => ['@type' => 'Person', '@id' => $home.'#person', 'name' => $portfolio['profile']['name'], 'url' => $home],
                    ],
                ],
            ],
        ]);
    }

    public function download(string $slug, string $key): StreamedResponse
    {
        $app = AppDownloads::app($slug);
        $build = collect($app['downloads'] ?? [])->firstWhere('key', $key);
        abort_unless($build && AppDownloads::info($slug, $key), 404);

        return Storage::disk('local')->download(
            AppDownloads::path($slug, $key),
            ($build['file'] ?? Str::studly($app['name'])).'.apk',
            ['Content-Type' => 'application/vnd.android.package-archive'],
        );
    }
}
