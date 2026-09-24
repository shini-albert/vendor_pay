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
        Schema::table('payment_approvals', function (Blueprint $table) {
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_approvals', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['workflow_step_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['role_id']);
        });
    }
};
