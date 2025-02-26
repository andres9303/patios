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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name', 500);
            $table->text('text')->nullable();
            $table->tinyInteger('state');
            $table->integer('concept')->nullable();
            $table->integer('type')->nullable();
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->foreignId('space_id')->nullable()->constrained('spaces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
