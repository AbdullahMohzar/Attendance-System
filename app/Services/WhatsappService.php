<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private static $instanceId = 'instance167988';
    private static $token = '3vpjtopne4w56lpw';

    /**
     * Check if the WhatsApp instance is authenticated and online.
     * Used by the Admin Dashboard status light.
     */
    public static function healthCheck()
    {
        $url = "https://api.ultramsg.com/" . self::$instanceId . "/instance/status";

        try {
            $response = Http::timeout(10)
                ->withoutVerifying() 
                ->get($url, [
                    'token' => self::$token
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // UltraMsg returns 'authenticated' or 'active' when linked
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

        // 1. CLEAN THE PHONE: Removes '+' and spaces
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        // 2. THE URL
        $url = "https://api.ultramsg.com/" . self::$instanceId . "/messages/chat";

        try {
            // 3. THE REQUEST: Using your working configuration
            $response = Http::timeout(30)
                ->withoutVerifying() 
                ->asForm()           
                ->post($url, [
                    'token' => self::$token,
                    'to'    => $cleanPhone,
                    'body'  => $message,
                ]);

            Log::info("WhatsApp API Response for {$cleanPhone}: " . $response->body());

        } catch (\Exception $e) {
            Log::error("WhatsApp Connection Error: " . $e->getMessage());
        }
    }
}



