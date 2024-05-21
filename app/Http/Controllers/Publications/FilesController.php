<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\FileService;

class FilesController extends Controller
{
    /**
     * Undocumented function
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $file = File::find($id);

        return FileService::download($file);
    }
}
