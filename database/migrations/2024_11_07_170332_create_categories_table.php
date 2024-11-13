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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->nullable();
            $table->string('name', 200);
            $table->text('text')->nullable();
            $table->integer('days')->nullable();
            $table->foreignId('ref_id')->nullable()->constrained('locations');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->boolean('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
