<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Send the given notification via Fonnte WhatsApp gateway.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        // Resolve the recipient's WhatsApp number
        $number = $notifiable->routeNotificationFor('whatsapp', $notification);

        if (empty($number)) {
            return; // Skip silently if no WA number is set
        }

        // Normalise number: convert 08xx → 628xx
        $number = $this->normaliseNumber($number);

        // Get message from notification
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message)) {
            return;
        }

        $token = config('fonnte.token');

        if (empty($token)) {
            Log::warning('[WhatsApp] FONNTE_TOKEN is not set. Skipping WA notification.');
            return;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => $token])
                ->post(config('fonnte.url'), [
                    'target'  => $number,
                    'message' => $message,
                ]);

            if (! $response->successful()) {
                Log::warning('[WhatsApp] Fonnte API error', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'target'  => $number,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[WhatsApp] Failed to send WA notification', [
                'error'  => $e->getMessage(),
                'target' => $number,
            ]);
        }
    }

    /**
     * Normalise Indonesian phone numbers to international format (62xxx).
     */
    private function normaliseNumber(string $number): string
    {
        // Remove spaces, dashes, parentheses
        $number = preg_replace('/[\s\-\(\)]+/', '', $number);

        if (str_starts_with($number, '08')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '+62')) {
            return substr($number, 1); // remove leading +
        }

        return $number;
    }
}
