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
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('landboard_id')->constrained('users')->onDelete('cascade');
            $table->string('external_id')->unique(); 
            $table->string('reference')->nullable(); 
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->decimal('amount', 15, 2);          
            $table->decimal('platform_fee', 15, 2);    
            $table->decimal('total_amount', 15, 2);    
            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED'])->default('PENDING');
            $table->text('description')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->index(['payment_id']);
            $table->index(['landboard_id']);
            $table->index(['external_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
