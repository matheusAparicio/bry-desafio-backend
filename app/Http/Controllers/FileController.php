<?php

namespace App\Http\Controllers;

use App\Exceptions\NoAttachedFileException;
use App\Models\DocumentFile;
use Auth;
use Illuminate\Http\Request;
use Storage;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:8192|mimes:pdf,jpg,jpeg',
        ]);

        $user = Auth::user();
        $file = $request->file('file');

        if ($user->file_id) {
            $old = DocumentFile::find($user->file_id);

            if ($old) {
                Storage::delete($old->path);
                $old->delete();
            }
        }

        $path = $file->store('documents');

        $document = DocumentFile::create([
            'path' => $path,
            'mime' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ]);

        $user->file_id = $document->id;
        $user->save();

        return response()->json([
            'message' => 'File successfully attached.',
            'file_id' => $document->id
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();

        if (!$user->file_id) {
            throw new NoAttachedFileException();
        }

        $document = DocumentFile::find($user->file_id);

        if ($document) {
            if (Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            $document->delete();
        }

        $user->file_id = null;
        $user->save();

        return response()->json([
            'message' => 'File successfully removed.'
        ]);
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user->file_id) {
            throw new NoAttachedFileException();
        }

        $document = DocumentFile::find($user->file_id);

        if (!$document || !Storage::exists($document->path)) {
            throw new NoAttachedFileException();
        }

        return response()->file(Storage::path($document->path));
    }
}
