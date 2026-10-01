<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\PageView;
use App\Support\PortfolioContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $days = in_array((int) $request->query('days'), [7, 30, 90], true) ? (int) $request->query('days') : 30;
        $content = PortfolioContent::get();
        $tz = $content['profile']['timezone'] ?? config('app.timezone');

        $start = now($tz)->subDays($days - 1)->startOfDay();
        $views = PageView::where('created_at', '>=', $start->copy()->utc())->get(['path', 'referrer', 'visitor', 'device', 'created_at']);

        // Bucket in PHP so days follow the owner's timezone, not the server's.
        $buckets = [];
        for ($day = $start->copy(); $day->lte(now($tz)); $day->addDay()) {
            $buckets[$day->toDateString()] = ['views' => 0, 'visitors' => []];
        }

        $home = $views->where('path', '/');

        foreach ($home as $view) {
            $key = Carbon::parse($view->created_at)->setTimezone($tz)->toDateString();
            if (isset($buckets[$key])) {
                $buckets[$key]['views']++;
                $buckets[$key]['visitors'][$view->visitor] = true;
            }
        }

        $series = [];
        foreach ($buckets as $day => $bucket) {
            $series[] = ['day' => $day, 'views' => $bucket['views'], 'visitors' => count($bucket['visitors'])];
        }

        return response()->json([
            'days' => $days,
            'series' => $series,
            'totals' => [
                'views' => $home->count(),
                'visitors' => $home->pluck('visitor')->unique()->count(),
                'today' => end($series)['views'] ?? 0,
                'resume' => $views->where('path', '/resume')->count(),
            ],
            'referrers' => $home->whereNotNull('referrer')->countBy('referrer')->sortDesc()->take(6)
                ->map(fn ($count, $host) => ['host' => $host, 'count' => $count])->values(),
            'devices' => [
                'desktop' => $home->where('device', 'desktop')->count(),
                'mobile' => $home->where('device', 'mobile')->count(),
            ],
            'messages' => [
                'unread' => ContactMessage::whereNull('read_at')->count(),
                'total' => ContactMessage::count(),
                'recent' => ContactMessage::latest()->take(3)->get(['id', 'name', 'company', 'message', 'read_at', 'created_at']),
            ],
            'available' => (bool) ($content['profile']['available'] ?? false),
        ]);
    }
}
