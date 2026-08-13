<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Send a WhatsApp message using Fonnte API.
     *
     * @param string $target Phone number (e.g. 0812xxxx or 62812xxxx)
     * @param string $message The message body
     * @return bool True if successful, False otherwise
     */
    public static function sendMessage(string $target, string $message): bool
    {
        $token = env('FONNTE_TOKEN');
        
        if (empty($token)) {
            Log::warning('Fonnte token is missing. WhatsApp message was not sent.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            if ($response->successful() && isset($response->json()['status']) && $response->json()['status'] == true) {
                return true;
            }

            Log::error('Fonnte Error Response: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
