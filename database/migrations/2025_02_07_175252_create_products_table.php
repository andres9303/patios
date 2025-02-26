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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable();
            $table->string('name', 500);
            $table->foreignId('unit_id')->constrained('units');
            $table->tinyInteger('state');
            $table->boolean('isinventory')->default(false);
            $table->integer('class')->nullable();
            $table->integer('type')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
