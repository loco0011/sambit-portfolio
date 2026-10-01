<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ContactMessage::latest()->take(500)->get());
    }

    public function update(Request $request, ContactMessage $message): JsonResponse
    {
        $request->validate(['read' => ['required', 'boolean']]);

        $message->forceFill(['read_at' => $request->boolean('read') ? now() : null])->save();

        return response()->json($message);
    }

    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json(['ok' => true]);
    }
}
