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
        //id, workflow_id, step_no, role_id, step_name
        Schema::create('workflow_steps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('workflow_rule_id')->constrained('workflow_rules')->onDelete('cascade');
        $table->integer('step_no');
        $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
        $table->string('step_name', 50);    
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
