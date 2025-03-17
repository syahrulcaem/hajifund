<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('business_proposals', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->string('documents');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_proposals');
    }
};
