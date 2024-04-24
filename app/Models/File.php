<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\FileService;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $quarde = false;
    protected $guarded = [];

    public function size()
    {
        return FileService::getSize($this->size);
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
