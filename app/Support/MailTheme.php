<?php

namespace App\Support;

/**
 * The site's dark palette and type, for emails. Email clients ignore stylesheets,
 * so the mail views inline these values on every element.
 */
final class MailTheme
{
    public const T = [
        'ink' => '#07070a',
        'card' => '#0c0c10',
        'fg' => '#ededef',
        'mute' => '#8b8b95',
        'dim' => '#55555e',
        'line' => '#1c1c22',
        'line2' => '#2a2a31',
        'acid' => '#d4ff4f',
        'acidBg' => '#141a08',
        'sans' => "'Geist', -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
        'mono' => "'Geist Mono', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace",
        'serif' => "'Instrument Serif', Georgia, 'Times New Roman', serif",
    ];
}
