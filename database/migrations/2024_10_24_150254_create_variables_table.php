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
        Schema::create('variables', function (Blueprint $table) {
            $table->id();            
            $table->string('cod', 10);
            $table->string('name', 100);
            $table->text('text')->nullable();
            $table->integer('concept')->nullable();
            $table->decimal('value', 16, 4)->nullable();
            $table->unsignedBigInteger('variable_id')->nullable();
            $table->timestamps();

            $table->foreign('variable_id')->references('id')->on('variables')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variables');
    }
};
