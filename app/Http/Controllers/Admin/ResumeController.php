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

    public function downloads(): JsonResponse
    {
        $all = fn () => PageView::where('path', '/resume');
        $details = $all()->whereNotNull('details')->pluck('details');

        $top = fn (string $key) => $details->pluck($key)->filter()->countBy()->sortDesc()->take(6)
            ->map(fn ($count, $name) => ['name' => $name, 'count' => $count])->values();

        return response()->json([
            'total' => $all()->count(),
            'last_7' => $all()->where('created_at', '>=', now()->subDays(7))->count(),
            'last_30' => $all()->where('created_at', '>=', now()->subDays(30))->count(),
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
