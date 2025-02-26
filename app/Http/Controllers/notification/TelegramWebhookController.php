<?php

namespace App\Http\Controllers\notification;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramCodeService;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public $codeService;

    public function __construct()
    {
        $this->codeService = app(TelegramCodeService::class);
    }

    public function handleWebhook(Request $request)
    {
        try {
            $data = $request->all();
            
            if (!isset($data['message']['text']) || !isset($data['message']['chat']['id'])) {
                throw new \Exception('Formato de mensaje inválido');
            }

            $text = $data['message']['text'];
            $chatId = $data['message']['chat']['id'];

            if (User::where('telegram_chat_id', $chatId)->exists())
            {
                $this->codeService->sendTelegramMessage($chatId, "✅ La cuenta ya está vinculada, el chat es de uso exclusivo para notificaciones.");
                return response()->json(['status' => 'success']);
            }

            if (str_starts_with($text, '/start')) {
                $this->codeService->sendTelegramMessage($chatId, "Bienvenido a la configuración de notificaciones! Ingresa el código generado por la plataforma para iniciar la vinculación.");
            }
            else if (strlen($text) == 6) {
                $this->codeService->sendTelegramMessage($chatId, $this->codeService->validateCode($text, $chatId));
            }
            else {
                $this->codeService->sendTelegramMessage($chatId, "❌ Código inválido. Por favor, ingresa el código de 6 dígitos generado por la plataforma.");
            }
                        
            return response()->json(['status' => 'success']);            
        } catch (\Exception $e) {            
            if (isset($chatId)) {
                $this->codeService->sendTelegramMessage($chatId, "❌ Error: ".$e->getMessage());

                return response()->json(['status' => 'success']);  
            }
            
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
