<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entrepreneur_details', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('npwp');
            $table->string('portfolio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrepreneur_details');
    }
};
