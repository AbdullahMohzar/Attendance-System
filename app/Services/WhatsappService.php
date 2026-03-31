<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Check if the WhatsApp instance is authenticated and online.
     * Uses environment variables for security.
     */
    public static function healthCheck()
    {
        $instanceId = env('WHATSAPP_INSTANCE_ID');
        $token = env('WHATSAPP_TOKEN');

        $url = "https://api.ultramsg.com/" . $instanceId . "/instance/status";

        try {
            $response = Http::timeout(10)
                ->withoutVerifying() 
                ->get($url, [
                    'token' => $token
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['status']) && ($data['status'] === 'authenticated' || $data['status'] === 'active');
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Health Check Error: " . $e->getMessage());
            return false;
        }

        return false;
    }

    /**
     * Send a WhatsApp message to a specific phone number.
     */
    public static function send($phone, $message)
    {
        if (!$phone) {
            Log::warning("WhatsApp skipped: No phone number provided.");
            return;
        }

        $instanceId = env('WHATSAPP_INSTANCE_ID');
        $token = env('WHATSAPP_TOKEN');

        // 1. CLEAN THE PHONE: Removes '+' and spaces
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        // 2. THE URL
        $url = "https://api.ultramsg.com/" . $instanceId . "/messages/chat";

        try {
            // 3. THE REQUEST
            $response = Http::timeout(30)
                ->withoutVerifying() 
                ->asForm()           
                ->post($url, [
                    'token' => $token,
                    'to'    => $cleanPhone,
                    'body'  => $message,
                ]);

            Log::info("WhatsApp API Response for {$cleanPhone}: " . $response->body());

        } catch (\Exception $e) {
            Log::error("WhatsApp Connection Error: " . $e->getMessage());
        }
    }
}