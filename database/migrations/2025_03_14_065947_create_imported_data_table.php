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
        Schema::create('imported_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dataset_id')->nullable();
            $table->json('row_data');
            $table->string('user_email'); // User yang terakhir mengupdate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_data');
    }
};
