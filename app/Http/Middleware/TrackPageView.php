<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Privacy-friendly analytics: no cookies, no raw IPs. A visitor is a daily-rotating hash.
 */
class TrackPageView
{
    private const BOTS = '/bot|crawl|spider|slurp|curl|wget|python|httpclient|headless|lighthouse|preview|facebookexternalhit|monitor|uptime/i';

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    // Runs after the response is sent, so tracking never slows the page down.
    public function terminate(Request $request, Response $response): void
    {
        $agent = (string) $request->userAgent();

        if (! $request->isMethod('GET') || ! $response->isSuccessful() || $agent === '' || preg_match(self::BOTS, $agent) || Auth::check()) {
            return;
        }

        try {
            PageView::create([
                'path' => '/'.ltrim($request->path(), '/'),
                'referrer' => $this->referrerHost($request),
                'visitor' => substr(hash('sha256', $request->ip().'|'.$agent.'|'.now()->toDateString().'|'.config('app.key')), 0, 16),
                'device' => preg_match('/mobile|android|iphone|ipad/i', $agent) ? 'mobile' : 'desktop',
            ]);
        } catch (Throwable) {
            // Analytics must never break the site.
        }
    }

    private function referrerHost(Request $request): ?string
    {
        $host = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);

        if (! $host || $host === $request->getHost()) {
            return null;
        }

        return substr(preg_replace('/^www\./', '', strtolower($host)), 0, 255);
    }
}
