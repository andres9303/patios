<?php

namespace App\Http\Controllers\notification;

use App\Http\Controllers\Controller;
use App\Services\TelegramCodeService;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    public function index(TelegramCodeService $codeService)
    {
        $user = Auth::user();
        $code = $codeService->generateCode($user);
        
        return view('config.notification.index', compact('code'));
    }

    public function telegramUnlink()
    {
        $user = Auth::user();
        $user->update([
            'telegram_chat_id' => null,
            'telegram_linked_at' => null
        ]);

        return redirect()->route('notification.index')->with('success', 'Telegram desvinculado correctamente');
    }
}
