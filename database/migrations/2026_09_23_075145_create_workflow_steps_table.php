<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->foreignId('rule_id')->nullable()->constrained('workflow_rules')->onDelete('cascade'); 
            $table->integer('step_no');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('step_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};