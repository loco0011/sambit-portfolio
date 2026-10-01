<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Honeypot: bots fill every field, humans never see this one.
        if (filled($request->input('website'))) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $message = ContactMessage::create($data + ['ip' => $request->ip()]);

        Log::info('New portfolio contact message', ['id' => $message->id, 'email' => $message->email]);

        // The message is already saved, so a mail outage shouldn't fail the request.
        if ($to = config('mail.contact_to')) {
            try {
                Mail::to($to)->send(new NewContactMessage($message));
            } catch (Throwable $e) {
                Log::error('Contact notification email failed', ['id' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
