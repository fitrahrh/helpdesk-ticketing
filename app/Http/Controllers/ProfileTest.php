<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_test_telegram_notification()
    {
        $user = User::factory()->create([
            'telegram_id' => '123456789'
        ]);

        // Mock request ke Telegram API
        Http::fake([
            '*' => Http::response(['ok' => true, 'result' => true], 200)
        ]);

        $this->actingAs($user);

        $response = $this->post(route('profile.test-telegram'), [
            'telegram_id' => '123456789',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Notifikasi berhasil dikirim ke Telegram Anda'
        ]);
    }
}