<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * APK files for the "After hours" apps, uploaded from the admin panel.
 *
 * Each app lists its builds in config `side_projects.*.downloads` (key + label).
 * A build lives at storage/app/apps/{slug}-{key}.apk with its SHA-256 beside it,
 * so the public page shows the real size and checksum of whatever was uploaded last.
 */
class AppDownloads
{
    public static function path(string $slug, string $key): string
    {
        return "apps/{$slug}-{$key}.apk";
    }

    /** @return array{size: int, updated_at: string, sha256: ?string}|null */
    public static function info(string $slug, string $key): ?array
    {
        $disk = Storage::disk('local');
        $path = self::path($slug, $key);

        if (! $disk->exists($path)) {
            return null;
        }

        return [
            'size' => $disk->size($path),
            'updated_at' => date(DATE_ATOM, $disk->lastModified($path)),
            'sha256' => $disk->exists("$path.sha256") ? trim($disk->get("$path.sha256")) : null,
        ];
    }

    public static function store(UploadedFile $file, string $slug, string $key): void
    {
        $disk = Storage::disk('local');
        $path = self::path($slug, $key);

        $file->storeAs(dirname($path), basename($path), 'local');
        $disk->put("$path.sha256", hash_file('sha256', $disk->path($path)));
    }

    /** The app from `side_projects` with this slug, or null. */
    public static function app(string $slug, ?array $content = null): ?array
    {
        $content ??= PortfolioContent::get();

        return collect($content['side_projects'] ?? [])->firstWhere('slug', $slug);
    }

    /** The app's builds, each with its upload info (size, date, checksum; null when nothing is uploaded yet). */
    public static function builds(array $app): array
    {
        return array_map(
            fn (array $build) => $build + ['upload' => self::info($app['slug'], $build['key'])],
            $app['downloads'] ?? [],
        );
    }
}
