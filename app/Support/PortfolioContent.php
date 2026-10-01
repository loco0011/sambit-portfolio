<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Site content: edits saved from the admin panel win, config/portfolio.php is the factory default.
 */
class PortfolioContent
{
    private const KEY = 'portfolio';

    public static function get(): array
    {
        return self::override()?->value ?? config('portfolio');
    }

    public static function override(): ?SiteSetting
    {
        return SiteSetting::find(self::KEY);
    }

    public static function save(array $content): SiteSetting
    {
        return SiteSetting::updateOrCreate(['key' => self::KEY], ['value' => $content]);
    }

    public static function reset(): void
    {
        SiteSetting::whereKey(self::KEY)->delete();
    }
}
