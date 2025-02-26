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
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus');
            $table->foreignId('company_id')->constrained('companies');
            $table->string('code', 20)->nullable();
            $table->string('num', 500)->nullable();
            $table->date('date')->nullable();
            $table->date('date2')->nullable();
            $table->date('date3')->nullable();
            $table->date('date4')->nullable();
            $table->decimal('subtotal', 16, 4)->nullable();
            $table->decimal('iva', 16, 4)->nullable();
            $table->decimal('discount', 16, 4)->nullable();
            $table->decimal('total', 16, 4)->nullable();
            $table->tinyInteger('state')->nullable();
            $table->text('text')->nullable();
            $table->integer('concept')->nullable();
            $table->integer('ref')->nullable();
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('person2_id')->nullable()->constrained('people');
            $table->foreignId('space_id')->nullable()->constrained('spaces');
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->foreignId('doc_id')->nullable()->constrained('docs');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('cant', 16, 4)->nullable();
            $table->decimal('saldo', 16, 4)->nullable();
            $table->decimal('value', 16, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
