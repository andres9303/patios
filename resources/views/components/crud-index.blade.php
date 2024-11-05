<div class="bg-white border-b border-gray-200">
    <!-- Alertas -->
    @if(session('info'))
      <div class="flex items-center bg-blue-100 text-blue-500 text-sm font-bold px-4 py-3" role="alert">
        <i class="fas fa-info-circle mr-2"></i>
        <span class="align-middle">{{ session('info') }}</span>
      </div>
    @endif

    @if(session('warning'))
      <div class="flex items-center bg-yellow-100 text-yellow-500 text-sm font-bold px-4 py-3" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span class="align-middle">{{ session('warning') }}</span>
      </div>
    @endif

    @if(session('success'))
      <div class="flex items-center bg-green-100 text-green-500 text-sm font-bold px-4 py-3" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <span class="align-middle">{{ session('success') }}</span>
      </div>
    @endif

    @if(session('error'))
      <div class="flex items-center bg-red-100 text-red-500 text-sm font-bold px-4 py-3" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span class="align-middle">{{ session('error') }}</span>
      </div>
    @endif

    <!-- Acciones -->
    @if(isset($actions))
    <div class="mt-8 border-b border-gray-200">
        <div class="flex justify-end m-2">
            <div class="flex items-center space-x-2">
                {{ $actions }}
            </div>
        </div>
    </div>
    @endif
    <!-- Contenido -->
    @if(isset($content))
    <div class="p-4 sm:px-15">
        {{ $content }}
    </div>
    @endif

    <!-- Pie de página -->
    <div class="mt-8 py-4 text-center text-xs text-gray-500">
        Copyright © <script>document.write(new Date().getFullYear())</script> - Desarrollado por
        <a href="https://mastic.com.co/" target="_blank" class="underline text-gray-600 font-bold">+TIC</a> - Todos los derechos reservados
      </div>
</div>
