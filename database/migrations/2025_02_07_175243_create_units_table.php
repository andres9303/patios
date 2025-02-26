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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->decimal('unit', 16, 4)->nullable();
            $table->decimal('time', 16, 4)->nullable();
            $table->decimal('mass', 16, 4)->nullable();
            $table->decimal('longitude', 16, 4)->nullable();
            $table->tinyInteger('state');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->decimal('factor', 16, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
