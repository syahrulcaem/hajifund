<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignUuid('investor_id')->constrained('users')->onDelete('cascade');
            $table->float('amount');
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investments');
    }
};
