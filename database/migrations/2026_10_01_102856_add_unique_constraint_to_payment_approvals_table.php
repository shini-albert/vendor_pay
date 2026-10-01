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
            $table->unique(['payment_id', 'workflow_step_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_approvals', function (Blueprint $table) {
            $table->dropUnique(['payment_id', 'workflow_step_id']);
        });
    }
};
