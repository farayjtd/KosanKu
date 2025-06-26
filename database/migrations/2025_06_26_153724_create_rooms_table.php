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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landboard_id')->constrained('landboards')->onDelete('cascade');
            $table->string('type');
            $table->enum('gender_type', ['male', 'female', 'mixed']);
            $table->decimal('price', 12, 2);
            $table->string('room_number')->unique();
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
