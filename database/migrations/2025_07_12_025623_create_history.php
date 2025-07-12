<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticket')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('status'); // 'created', 'assigned', 'status_changed'
            $table->json('old_values')->nullable(); // Nilai lama jika ada perubahan
            $table->json('new_values')->nullable(); // Nilai baru jika ada perubahan
            $table->text('keterangan')->nullable(); // Keterangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
