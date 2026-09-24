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
        //id, payment_no, vendor_id, amount, payment_date, description, workflow_id, status, current_step_no, created_by, created_at
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 50);
            $table->foreignId('vendor_id');
            $table->integer('amount');
            $table->date('payment_date');
            $table->text('description')->nullable();
            $table->foreignId('workflow_id');
            $table->string('status', 20)->default('pending');
            $table->integer('current_step_no')->default(0);
            $table->foreignId('created_by');
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
