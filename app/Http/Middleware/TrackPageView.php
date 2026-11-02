<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Support\GeoIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Analytics without cookies. A visitor is a daily-rotating hash, and raw IPs are kept only for résumé downloads.
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

        $isResume = $request->routeIs('resume');

        // Every résumé download counts, including the owner's own, which are labelled in the admin panel.
        if (! $request->isMethod('GET') || ! $response->isSuccessful() || $agent === '' || preg_match(self::BOTS, $agent) || (Auth::check() && ! $isResume)) {
            return;
        }

        try {
            PageView::create([
                'path' => '/'.ltrim($request->path(), '/'),
                'referrer' => $this->referrerHost($request),
                'visitor' => substr(hash('sha256', $request->ip().'|'.$agent.'|'.now()->toDateString().'|'.config('app.key')), 0, 16),
                'device' => preg_match('/mobile|android|iphone|ipad/i', $agent) ? 'mobile' : 'desktop',
                'details' => $isResume ? $this->downloadDetails($request, $agent) : null,
            ]);
        } catch (Throwable $e) {
            // Analytics must never break the site, but a lost count should show up in the logs.
            Log::warning('Page view not recorded', ['path' => $request->path(), 'error' => $e->getMessage()]);
        }
    }

    // Who downloaded the résumé, as far as a request can tell.
    private function downloadDetails(Request $request, string $agent): array
    {
        $referer = (string) $request->headers->get('referer');
        $language = strtok((string) $request->header('Accept-Language'), ',;');

        return array_filter(app(GeoIp::class)->lookup((string) $request->ip()) + [
            'ip' => $request->ip(),
            'admin' => Auth::check(),
            'browser' => $this->firstMatch($agent, ['Edge' => 'Edg', 'Opera' => 'OPR', 'Samsung Internet' => 'SamsungBrowser', 'Chrome' => 'Chrome|CriOS', 'Firefox' => 'Firefox|FxiOS', 'Safari' => 'Safari']),
            'os' => $this->firstMatch($agent, ['Android' => 'Android', 'iOS' => 'iPhone|iPad', 'Windows' => 'Windows', 'macOS' => 'Mac OS X', 'Linux' => 'Linux']),
            'language' => $language && preg_match('/^[a-z]{2,3}(-[a-z0-9]{2,8})*$/i', $language) ? substr($language, 0, 35) : null,
            // The page on this site the download link was clicked on.
            'page' => parse_url($referer, PHP_URL_HOST) === $request->getHost() ? substr((string) (parse_url($referer, PHP_URL_PATH) ?: '/'), 0, 120) : null,
        ]);
    }

    private function firstMatch(string $agent, array $patterns): ?string
    {
        foreach ($patterns as $name => $pattern) {
            if (preg_match("/$pattern/", $agent)) {
                return $name;
            }
        }

        return null;
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
