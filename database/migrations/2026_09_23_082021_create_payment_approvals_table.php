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
        //id, payment_id, workflow_step_id, user_id, role_id, action, remarks, acted_at
        Schema::create('payment_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id');
            $table->foreignId('workflow_step_id');
            $table->foreignId('user_id');
            $table->foreignId('role_id');
            $table->string('action', 20);
            $table->text('remarks')->nullable();    
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_approvals');
    }
};
