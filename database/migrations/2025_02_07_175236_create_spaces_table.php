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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->text('text')->nullable();
            $table->integer('order')->nullable();
            $table->tinyInteger('state');
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->foreignId('item2_id')->nullable()->constrained('items');
            $table->decimal('cant', 16, 4)->nullable();
            $table->foreignId('space_id')->nullable()->constrained('spaces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
