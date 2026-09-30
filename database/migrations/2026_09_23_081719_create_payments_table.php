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
            $table->string('payment_no')->unique();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->text('description')->nullable();
            $table->foreignId('workflow_id')->constrained('workflows');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('current_step_no')->default(1);
            $table->foreignId('created_by')->constrained('users');
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
