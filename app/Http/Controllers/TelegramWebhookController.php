<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Services\TelegramService;


class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming webhook requests from Telegram.
     */
public function handle(Request $request)
{
    try {
        Log::info('Telegram Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
        ]);

        $update = $request->all();
        $apiToken = config('telegram.bots.mybot.token');

        if (isset($update['message'])) {
            $message = $update['message'];
            $text = $message['text'] ?? '';
            $chatId = $message['chat']['id'];
            $firstName = $message['from']['first_name'] ?? 'User';

            if ($text === '/start' || $text === '/getid') {
                $response = "👋 Selamat datang di Bot Helpdesk, $firstName!\n\n" .
                            "🆔 *Telegram ID Anda adalah:* `$chatId`\n\n" .
                            "✅ Silakan salin ID ini dan masukkan ke profil Anda di Sistem Helpdesk untuk menerima notifikasi tiket.\n\n" .
                            "📝 *Langkah-langkah:*\n" .
                            "1. Kembali ke website Helpdesk\n" .
                            "2. Buka menu Profil\n" .
                            "3. Tempel Telegram ID pada kolom 'Telegram ID'\n" .
                            "4. Klik 'Update'\n\n" .
                            "💡 Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

                Http::post("https://api.telegram.org/bot{$apiToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $response,
                    'parse_mode' => 'Markdown'
                ]);
            }
            elseif ($text === '/help') {
                $helpMessage = "ℹ️ *Bantuan Helpdesk Bot*\n\n" .
                               "Bot ini mengirimkan notifikasi tiket dari sistem Helpdesk.\n\n" .
                               "*Perintah yang tersedia:*\n" .
                               "• */start* - Memulai bot dan mendapatkan Chat ID\n" .
                               "• */getid* - Mendapatkan Chat ID Anda\n" .
                               "• */help* - Menampilkan bantuan\n" .
                               "• */test* - Mengirim notifikasi uji coba\n\n" .
                               "Jika Anda mengalami masalah, silakan hubungi administrator.";

                Http::post("https://api.telegram.org/bot{$apiToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $helpMessage,
                    'parse_mode' => 'Markdown'
                ]);
            }
            elseif ($text === '/test') {
                $userExists = User::where('telegram_id', $chatId)->exists();

                if ($userExists) {
                    $testMessage = "✅ *Notifikasi Berhasil!*\n\n" .
                                   "Akun Anda telah terhubung dengan sistem notifikasi Helpdesk.\n" .
                                   "Anda akan menerima notifikasi saat ada tiket baru atau perubahan status.";

                    Http::post("https://api.telegram.org/bot{$apiToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $testMessage,
                        'parse_mode' => 'Markdown'
                    ]);
                } else {
                    $notRegisteredMessage = "❌ *Chat ID Belum Terdaftar*\n\n" .
                                            "Chat ID `$chatId` belum terhubung dengan akun Helpdesk manapun.\n\n" .
                                            "Silakan daftarkan Chat ID ini di profil Anda pada aplikasi Helpdesk terlebih dahulu.";

                    Http::post("https://api.telegram.org/bot{$apiToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $notRegisteredMessage,
                        'parse_mode' => 'Markdown'
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    } catch (\Exception $e) {
        Log::error('Error handling Telegram Webhook: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}