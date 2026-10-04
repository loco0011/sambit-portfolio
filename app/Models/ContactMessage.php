<?php

namespace App\Models;

use App\Support\UserAgent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'email', 'company', 'message', 'ip', 'meta'];

    protected $appends = ['details'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime', 'meta' => 'array'];
    }

    /**
     * The sender's context as ordered [label => value] rows for the inbox and the
     * notification email. Rows without a value are left out.
     */
    protected function details(): Attribute
    {
        return Attribute::get(function () {
            $meta = $this->meta ?? [];
            $agent = UserAgent::describe($meta['user_agent'] ?? null);
            $device = collect([$agent['browser'], $agent['os'] ? 'on '.$agent['os'] : null])->filter()->implode(' ');

            $utm = collect(['source', 'medium', 'campaign'])
                ->map(fn ($k) => $meta['utm'][$k] ?? null)
                ->filter()
                ->implode(' / ');

            return array_filter([
                'Country' => $meta['country'] ?? null,
                'Device' => $device ? "$device · {$agent['device']}" : null,
                'Language' => $meta['language'] ?? null,
                'Timezone' => $meta['timezone'] ?? null,
                'Screen' => $meta['screen'] ?? null,
                'Came from' => isset($meta['referrer']) ? self::referrerLabel($meta['referrer']) : (isset($meta['user_agent']) ? 'Direct visit' : null),
                'Campaign' => $utm ?: null,
                'IP address' => $this->ip,
            ]);
        });
    }

    private static function referrerLabel(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return $host ? preg_replace('/^www\./', '', $host) : $url;
    }
}
