<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\FileService;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $quarde = false;
    protected $guarded = [];

    public function size()
    {
        FileService::getSize($this->size);
    }
}
