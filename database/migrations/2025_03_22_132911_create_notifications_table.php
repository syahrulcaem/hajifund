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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Bisa null jika broadcast
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Isi notifikasi
            $table->enum('status', ['UNREAD', 'READ'])->default('UNREAD'); // Status notifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
