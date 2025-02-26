<x-app-layout>
    <x-slot name="header">
        Configuración - Notificaciones
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            
        </x-slot>

        <x-slot name="content">
        <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Notificación Telegram</h2>

                @if(auth()->user()->telegram_chat_id)
                    <div class="bg-white rounded-lg shadow p-6 flex items-center space-x-4">
                        <div class="flex-1">
                            <p class="text-lg text-gray-700 mb-2">
                                <i class="text-2xl text-blue-500 fab fa-telegram-plane"></i>
                                Ya se encuentra vinculado su chat de Telegram.                               
                            </p>
                            <p class="text-sm text-gray-500">
                                Chat Vinculado: {{ auth()->user()->telegram_linked_at }}
                            </p>
                            <button onclick="location.href='{{ route('notification.telegram.unlink') }}'"
                                    class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600 mt-4">
                                Desvincular
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <p class="text-lg text-gray-700 mb-2">
                                <i class="text-2xl text-blue-500 fab fa-telegram-plane"></i>
                                    Vincula tu chat de Telegram                                
                                </p>
                                <ol class="list-decimal list-inside text-sm text-gray-500 space-y-1">
                                    <li>
                                        <strong>1.</strong> Ingresa a Telegram y busca el bot 
                                        <a href="http://t.me/MasticNotificationBot" target="_blank" class="text-blue-600 font-semibold">
                                            @MasticNotificationBot
                                        </a>
                                        .
                                    </li>
                                    <li>
                                        <strong>2.</strong> Presiona el botón <strong>INICIAR</strong> o envía el comando <strong><code>/star</code></strong>.
                                    </li>
                                    <li>
                                        <strong>3.</strong> Ingresa el código que se proporciona a continuación.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="mt-4">
                            {!! Html::text('code', $code ?? '')
                                ->class('mt-1 block bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500')
                                ->attribute('placeholder', 'Ingrese el código de vinculación') !!}
                            <p class="text-xs text-gray-500 mt-1">
                                Código vence:
                                @if(auth()->user()->telegram_code_expires_at)
                                    {{ auth()->user()->telegram_code_expires_at->format('d/m/Y H:i') }}
                                @else
                                    No asignado
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>