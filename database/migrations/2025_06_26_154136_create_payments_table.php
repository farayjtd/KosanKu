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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_history_id')->constrained('rental_histories')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('landboard_id')->constrained('landboards')->onDelete('cascade');
            $table->string('invoice_id')->unique();
            $table->string('external_id')->nullable();
            $table->string('reference')->nullable(); 
            $table->decimal('amount', 12, 2);               
            $table->decimal('penalty_amount', 12, 2)->default(0); 
            $table->decimal('total_amount', 12, 2);        
            $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); 
            $table->date('due_date')->nullable();          
            $table->timestamp('paid_at')->nullable();      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
