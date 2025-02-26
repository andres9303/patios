<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Configurar webhook para Telegram';

    public function handle()
    {
        $client = new Client();
        $url = env('APP_URL').'/telegram/webhook/'.env('TELEGRAM_WEBHOOK_SECRET');
        
        $response = $client->post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/setWebhook", [
            'form_params' => [
                'url' => $url
            ]
        ]);
        
        $this->info('Webhook configurado: '.$response->getBody());
    }
}