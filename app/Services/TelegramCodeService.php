<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class TelegramCodeService
{
    public function generateCode(User $user): string
    {
        do
        {
            $code = Str::random(6);
        } while (User::where('telegram_code', $code)->exists());

        $user->update([
            'telegram_code' => $code, 
            'telegram_code_expires_at' => now()->addMinutes(30) 
        ]);

        return $code;
    }

    public function validateCode(string $code, string $chatId): string
    {
        try{
            $user = User::where('telegram_code', $code)->first();

            if (!$user) {
                return '❌ Error: Código inválido';
            }

            if ($user->telegram_chat_id) {
                return '✅ La cuenta ya está vinculada';
            }
            
            if (now()->gt($user->telegram_code_expires_at)) {
                return '❌ Error: El código ha expirado';
            }
            
            $user->update([
                'telegram_code' => null,
                'telegram_code_expires_at' => null,
                'telegram_chat_id' => $chatId,
                'telegram_linked_at' => now()
            ]);

            return '✅ Cuenta vinculada correctamente con: '.$user->name;
        }
        catch (\Exception $e) {
            return '❌ Error VALIDATE: '.$e->getMessage();
        }        
    }

    public function sendTelegramMessage($chatId, $message)
    {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $message,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error al enviar mensaje a Telegram');
        }
    }
}