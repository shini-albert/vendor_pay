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
        Schema::table('payments', function (Blueprint $table) {
                $table->foreign('vendor_id')
          ->references('id')
          ->on('vendors');
             $table->foreign('workflow_id')
          ->references('id')
          ->on('workflows');
           $table->foreign('created_by')
          ->references('id')
          ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
