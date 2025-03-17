<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->float('revenue');
            $table->float('profit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
