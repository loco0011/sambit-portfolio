<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:pdf', 'max:10240']]);

        $request->file('file')->storeAs('', self::FILE, 'local');

        return $this->show();
    }
}
