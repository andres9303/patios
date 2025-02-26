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
        Schema::create('mvtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_id')->constrained('docs');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->decimal('cant', 16, 4)->nullable();
            $table->decimal('saldo', 16, 4)->nullable();
            $table->decimal('valueu', 16, 4)->nullable();
            $table->decimal('iva', 16, 4)->nullable();
            $table->decimal('valuet', 16, 4)->nullable();
            $table->text('text')->nullable();
            $table->tinyInteger('state');
            $table->foreignId('product2_id')->nullable()->constrained('products');
            $table->foreignId('unit2_id')->nullable()->constrained('units');
            $table->decimal('cant2', 16, 4)->nullable();
            $table->decimal('saldo2', 16, 4)->nullable();
            $table->decimal('valueu2', 16, 4)->nullable();
            $table->decimal('iva2', 16, 4)->nullable();
            $table->decimal('valuet2', 16, 4)->nullable();
            $table->text('text2')->nullable();
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->foreignId('mvto_id')->nullable()->constrained('mvtos');
            $table->integer('concept')->nullable();
            $table->integer('ref')->nullable();
            $table->decimal('costu', 16, 4)->nullable();
            $table->foreignId('activity_id')->nullable()->constrained('activities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mvtos');
    }
};
