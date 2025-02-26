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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_id')->nullable()->constrained('docs');
            $table->foreignId('menu_id')->constrained('menus');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->decimal('cant', 10, 2)->default(1);
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->foreignId('activity_id')->nullable()->constrained('activities');
            $table->foreignId('space_id')->nullable()->constrained('spaces');
            $table->text('text')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
