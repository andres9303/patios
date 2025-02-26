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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('code', 20);
            $table->string('name', 500);
            $table->foreignId('unit_id')->constrained('units');
            $table->text('text')->nullable();
            $table->tinyInteger('state');
            $table->decimal('cant', 16, 4)->nullable();
            $table->decimal('value', 16, 4)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('type')->nullable();
            $table->foreignId('activity_id')->nullable()->constrained('activities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
