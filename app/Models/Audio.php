<?php

namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $table = 'audios';
    protected $quarde = false;
    protected $guarded = [];

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function size()
    {
        return FileService::getSize($this->size);
    }
}
