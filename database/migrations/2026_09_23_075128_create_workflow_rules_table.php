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
        //id, workflow_id, field, operator, value
        Schema::create('workflow_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id');
            $table->string('field', 50);
            $table->string('operator', 5);
             $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_rules');
    }
};
