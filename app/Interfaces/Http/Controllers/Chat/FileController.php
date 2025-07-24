<?php

namespace App\Interfaces\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Interfaces\Http\Requests\Chat\UploadFileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class FileController
{
    /**
     * Handle file upload and return file URL and ID.
     */
    public function upload(UploadFileRequest $request)
    {
        // Store the uploaded file in the 'uploads' disk/folder
        $file = $request->file('file');
        $path = $file->store('uploads');
        // You may want to save file info to DB and return an ID
        // For now, just return the path as file_id
        return response()->json([
            'url' => Storage::url($path),
            'file_id' => $path,
        ], 201);
    }

    /**
     * Retrieve and return the file by its ID (path).
     */
    public function show($id)
    {
        // $id is the file path or unique identifier
        if (!Storage::exists($id)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($id);
    }
} 