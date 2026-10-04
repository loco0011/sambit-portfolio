<?php

namespace App\Http\Controllers;

use App\Mail\ContactReceived;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        $message = ContactMessage::create($data + ['ip' => $request->ip(), 'meta' => $this->context($request)]);

        Log::info('New portfolio contact message', ['id' => $message->id, 'email' => $message->email]);

        // The message is already saved, so a mail outage shouldn't fail the request.
        if ($to = config('mail.contact_to')) {
            try {
                Mail::to($to)->send(new NewContactMessage($message));
            } catch (Throwable $e) {
                Log::error('Contact notification email failed', ['id' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        // Confirmation back to the sender, so they know it arrived.
        try {
            Mail::to($message->email, $message->name)->send(new ContactReceived($message));
        } catch (Throwable $e) {
            Log::error('Contact auto-reply email failed', ['id' => $message->id, 'error' => $e->getMessage()]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Non-sensitive context about the sender: what the browser reports about itself,
     * where they came from and campaign tags. No fingerprinting, no third-party lookups;
     * country is only recorded when a proxy in front of the app already supplies it.
     */
    private function context(Request $request): array
    {
        $client = function (string $key, int $max) use ($request): ?string {
            $value = $request->input("meta.$key");

            return is_string($value) && trim($value) !== '' ? Str::limit(strip_tags(trim($value)), $max, '') : null;
        };

        $utm = array_filter([
            'source' => $client('utm.source', 80),
            'medium' => $client('utm.medium', 80),
            'campaign' => $client('utm.campaign', 120),
        ]);

        $country = $request->header('CF-IPCountry') ?? $request->header('CloudFront-Viewer-Country') ?? $request->header('X-Country-Code');
        $referrer = $client('referrer', 500);
        $screen = $client('screen', 11);

        return array_filter([
            'user_agent' => Str::limit((string) $request->userAgent(), 400, '') ?: null,
            'country' => is_string($country) && preg_match('/^[A-Z]{2}$/', $country) && $country !== 'XX' ? $country : null,
            'referrer' => $referrer && filter_var($referrer, FILTER_VALIDATE_URL) ? $referrer : null,
            'language' => $client('language', 35),
            'timezone' => $client('timezone', 64),
            'screen' => $screen && preg_match('/^\d{2,5}x\d{2,5}$/', $screen) ? $screen : null,
            'utm' => $utm ?: null,
        ]);
    }
}
