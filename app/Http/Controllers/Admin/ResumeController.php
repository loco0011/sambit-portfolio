<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    private const FILE = 'resume.pdf';

    public function show(): JsonResponse
    {
        $disk = Storage::disk('local');

        if (! $disk->exists(self::FILE)) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'size' => $disk->size(self::FILE),
            'updated_at' => date(DATE_ATOM, $disk->lastModified(self::FILE)),
        ]);
    }

    /** Date ranges the admin can filter downloads by. Days follow the admin's own timezone. */
    private const RANGES = ['today', 'yesterday', '7d', '30d', 'all'];

    public function downloads(Request $request): JsonResponse
    {
        $range = in_array($request->query('range'), self::RANGES, true) ? $request->query('range') : 'all';
        $tz = in_array($request->query('tz'), timezone_identifiers_list(), true) ? $request->query('tz') : config('app.timezone');

        $today = now($tz)->startOfDay();
        [$from, $to] = match ($range) {
            'today' => [$today, null],
            'yesterday' => [$today->copy()->subDay(), $today],
            '7d' => [$today->copy()->subDays(6), null],
            '30d' => [$today->copy()->subDays(29), null],
            'all' => [null, null],
        };

        $all = fn () => PageView::where('path', '/resume')
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from->copy()->setTimezone(config('app.timezone'))))
            ->when($to, fn ($q) => $q->where('created_at', '<', $to->copy()->setTimezone(config('app.timezone'))));
        $details = $all()->whereNotNull('details')->pluck('details');

        $top = fn (string $key) => $details->pluck($key)->filter()->countBy()->sortDesc()->take(6)
            ->map(fn ($count, $name) => ['name' => $name, 'count' => $count])->values();

        return response()->json([
            'range' => $range,
            'count' => $all()->count(),
            'total' => PageView::where('path', '/resume')->count(),
            'countries' => $top('country'),
            'orgs' => $top('org'),
            'recent' => $all()->latest('created_at')->latest('id')->paginate(15, ['id', 'referrer', 'device', 'details', 'created_at']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:pdf', 'max:10240']]);

        $request->file('file')->storeAs('', self::FILE, 'local');

        return $this->show();
    }
}
