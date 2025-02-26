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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable()->unique(); // Almacena el chat_id cifrado
            $table->string('telegram_code')->nullable(); // Almacena el código temporal
            $table->timestamp('telegram_code_expires_at')->nullable(); // Fecha de expiración del código
            $table->timestamp('telegram_linked_at')->nullable(); // Fecha de vinculación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'telegram_code', 'telegram_code_expires_at', 'telegram_linked_at']);
        });
    }
};
