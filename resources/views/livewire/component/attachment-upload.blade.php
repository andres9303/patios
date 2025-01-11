<div>
    <div class="mx-auto max-w-3xl px-4 py-6">
        @if (session()->has('message'))
            <div id="flash-message" class="mb-4 flex items-center justify-between rounded-md bg-green-100 p-4 text-sm text-green-800">
                <span>{{ session('message') }}</span>
                <button type="button" class="ml-2 text-green-600 hover:text-green-900" onclick="document.getElementById('flash-message').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    
        <div class="mb-6">
            <h4 class="mb-2 text-lg font-semibold">Archivos Adjuntos Existentes</h4>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($attachments as $attachment)
                    <div class="flex w-48 flex-col items-center rounded border border-gray-200 bg-white p-4">
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
                                default:
                                    $icon = 'fas fa-file text-gray-500';
                                    break;
                            }
                        @endphp
                        <i class="{{ $icon }} fa-2x mb-2"></i>
                        <span class="mb-2 text-center text-sm line-clamp-2">
                            {{ $attachment->filename }}
                        </span>
                        <div class="mt-auto flex space-x-2">
                            <a  href="{{ asset($attachment->filepath) }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Ver archivo">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form style="display:inline;" action="{{ route('attachments.destroy', ['attachment' => $attachment->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar archivo">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>    
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    
        <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data" class="rounded bg-gray-100 p-4 shadow" >
            @csrf
            <input type="hidden" name="attachmentable_type" value="{{ $attachmentableType }}">
            <input type="hidden" name="attachmentable_id" value="{{ $attachmentableId }}">
    
            <div class="mb-4">
                <label for="files" class="mb-2 block font-medium text-gray-700">
                    Nuevos Archivos:
                </label>
                <input type="file" name="files[]" id="files" multiple class="w-full cursor-pointer rounded border border-gray-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
    
            <button type="submit" class="inline-flex items-center rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-upload mr-2"></i>Subir Archivos
            </button>
        </form>
    </div>
</div>