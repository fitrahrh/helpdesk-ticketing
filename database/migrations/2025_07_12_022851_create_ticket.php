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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->string('no_tiket')->unique();
            $table->foreignUuid('user_id')->nullable()->constrained('users');
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris');
            $table->foreignUuid('approved_by')->nullable()->constrained('users'); // Untuk approval workflow
            $table->timestamp('approved_at')->nullable(); // Kapan disetujui
            $table->foreignUuid('assigned_to')->nullable()->constrained('users'); // Teknisi yang ditugaskan
            $table->timestamp('assigned_at')->nullable(); // Kapan ditugaskan
            $table->foreignUuid('last_comment_by')->nullable()->constrained('users'); // Siapa yang terakhir komentar
            $table->timestamp('last_comment_at')->nullable(); // Kapan komentar terakhir
            $table->foreignUuid('closed_by')->nullable()->constrained('users'); // Siapa yang menutup tiket
            $table->timestamp('closed_at')->nullable(); // Kapan tiket ditutup
            $table->string('judul');
            $table->text('masalah');
            $table->enum('status', ['Baru', 'Diproses', 'Disposisi', 'Selesai'])->default('Baru');
            $table->enum('urgensi', ['Rendah', 'Sedang', 'Tinggi', 'Mendesak', 'Standby'])->default('Rendah');
            $table->json('lampiran')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
