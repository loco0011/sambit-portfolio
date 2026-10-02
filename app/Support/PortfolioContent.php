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
        $defaults = config('portfolio');
        $saved = self::override()?->value;

        if (! $saved) {
            return $defaults;
        }

        // Saved edits win, but sections (and object fields) added to the config after the
        // last save are filled in from the defaults. Lists are never merged: a saved list
        // with fewer items stays that way.
        foreach ($defaults as $key => $value) {
            if (! array_key_exists($key, $saved)) {
                $saved[$key] = $value;
            } elseif (is_array($value) && ! array_is_list($value) && is_array($saved[$key])) {
                $saved[$key] += $value;
            }
        }

        return $saved;
    }

    /**
     * Every project with its own /work/{slug} page: the featured projects, then the extra case studies.
     *
     * @return array<string, array>
     */
    public static function caseStudies(?array $content = null): array
    {
        $content ??= self::get();
        $all = [];

        foreach ([...($content['projects'] ?? []), ...($content['case_studies'] ?? [])] as $project) {
            if (! empty($project['slug'])) {
                $all[$project['slug']] = $project;
            }
        }

        return $all;
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
