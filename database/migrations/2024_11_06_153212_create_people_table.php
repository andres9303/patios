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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('identification', 20)->unique();
            $table->string('name', 250);
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('whatsapp', 100)->nullable();
            $table->string('telegram', 100)->nullable();
            $table->text('text')->nullable();
            $table->date('birth')->nullable();
            $table->boolean('isClient')->default(false);
            $table->boolean('isSupplier')->default(false);
            $table->boolean('isEmployee')->default(false);
            $table->tinyInteger('state')->default(1)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
