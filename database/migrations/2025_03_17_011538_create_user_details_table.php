<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fullName');
            $table->string('phone');
            $table->string('ktpNumber')->unique();
            $table->string('bankAccount');
            $table->boolean('is_approved')->default(false); // Tambahan kolom ACC admin
            $table->string('ktpImage')->nullable(); // Menyimpan path foto KTP
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};
