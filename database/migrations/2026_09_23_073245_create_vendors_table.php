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
        //id, name, vendor_type, is_active
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
              $table->string('name',50);    
               $table->string('vendor_type',10);
               $table->char('is_active', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
