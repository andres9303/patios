<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachmentable_type' => 'required|string',
            'attachmentable_id'   => 'required|integer',
            'files.*'            => 'required|file|max:10240', // 10 MB por archivo
        ]);

        $modelClass = $request->input('attachmentable_type');
        $model      = app($modelClass)::find($request->input('attachmentable_id'));
        
        if (!$model) {
            return back()->with('message', 'Modelo no encontrado.');
        }

        foreach ($request->file('files', []) as $file) {
            $folderName      = class_basename($model) . Carbon::now()->format('/Y-m-d');
            $destinationPath = public_path('storage/' . $folderName);

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move($destinationPath, $uniqueName);

            $model->attachments()->create([
                'filename' => $file->getClientOriginalName(),
                'filepath' => "storage/$folderName/$uniqueName",
            ]);
        }

        return back()->with('message', 'Archivos subidos exitosamente.');
    }

    public function destroy(Attachment $attachment)
    {
        Storage::delete($attachment->filepath);
        $attachment->delete();

        return back()->with('message', 'Archivo eliminado exitosamente.');
    }
}
