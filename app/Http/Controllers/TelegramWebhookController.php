<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming webhook requests from Telegram.
     */
    public function handle(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Telegram Webhook received', [
                'headers' => $request->headers->all(),
                'body' => $request->getContent(),
            ]);

            // Process the webhook payload
            $update = $request->all();

            // Check if the message text is "/start"
            if (isset($update['message']['text']) && $update['message']['text'] === '/start') {
                $chatId = $update['message']['chat']['id'];
                $responseText = "Halo! Telegram ID Anda adalah: $chatId";

                // Send response back to the user
                \Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);

                Log::info('Telegram ID sent: ' . $chatId);
            }

            // Return a success response to Telegram
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error handling Telegram Webhook: ' . $e->getMessage());

            // Return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}