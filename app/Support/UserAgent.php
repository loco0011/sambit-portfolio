<?php

namespace App\Support;

/** Turns a User-Agent string into a readable browser, OS and device type. Good enough for a contact inbox; not a full parser. */
final class UserAgent
{
    /** @return array{browser: ?string, os: ?string, device: string} */
    public static function describe(?string $ua): array
    {
        $ua = (string) $ua;

        return [
            'browser' => self::browser($ua),
            'os' => self::os($ua),
            'device' => self::device($ua),
        ];
    }

    private static function browser(string $ua): ?string
    {
        // Order matters: most browsers also claim to be Chrome and/or Safari.
        $browsers = [
            'Edge' => '/Edg(?:e|A|iOS)?\/(\d+)/',
            'Opera' => '/(?:OPR|Opera)\/(\d+)/',
            'Samsung Internet' => '/SamsungBrowser\/(\d+)/',
            'Firefox' => '/(?:Firefox|FxiOS)\/(\d+)/',
            'Chrome' => '/(?:Chrome|CriOS)\/(\d+)/',
            'Safari' => '/Version\/(\d+)[\d.]* .*Safari\//',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $ua, $m)) {
                return "$name {$m[1]}";
            }
        }

        return null;
    }

    private static function os(string $ua): ?string
    {
        return match (true) {
            (bool) preg_match('/iPhone|iPad|iPod/', $ua) => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS X') => 'macOS',
            str_contains($ua, 'CrOS') => 'ChromeOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => null,
        };
    }

    private static function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/iPad|Tablet|Android(?!.*Mobile)/', $ua) => 'Tablet',
            (bool) preg_match('/Mobi|iPhone|iPod/', $ua) => 'Mobile',
            default => 'Desktop',
        };
    }
}
