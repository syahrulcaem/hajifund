<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('entrepreneur_id')->constrained('entrepreneur_details')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->float('fundingGoal');
            $table->dateTime('deadline');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};
