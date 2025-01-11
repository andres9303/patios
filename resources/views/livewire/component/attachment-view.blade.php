<div>
    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($attachments as $attachment)
                @php
                    $extension = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                    $icon = 'fas fa-file';
                    switch (strtolower($extension)) {
                        case 'pdf':
                            $icon = 'fas fa-file-pdf text-red-500';
                            break;
                        case 'doc':
                        case 'docx':
                            $icon = 'fas fa-file-word text-blue-500';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icon = 'fas fa-file-excel text-green-500';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $icon = 'fas fa-file-image text-yellow-500';
                            break;
                    }
                @endphp
    
                <!-- Enlace completo clicable para descargar -->
                <a 
                    href="{{ asset($attachment->filepath) }}"
                    download
                    class="group flex items-center rounded border border-gray-200 bg-white py-3 px-4 transition hover:bg-gray-100"
                >
                    <i class="{{ $icon }} fa-2x mr-4"></i>
                    <div class="flex flex-col">
                        <span class="font-medium line-clamp-1 group-hover:underline">
                            {{ $attachment->filename }}
                        </span>
                        <span class="text-xs text-gray-500">
                            Subido el {{ $attachment->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
