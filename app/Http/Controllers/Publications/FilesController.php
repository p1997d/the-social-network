<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function download($id)
    {
        $file = File::find($id);

        if (Storage::exists($file->path)) {
            return response()->download(storage_path('app/' . $file->path));
        } else {
            abort(404, 'File not found');
        }
    }
}
