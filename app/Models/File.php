<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;
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

    public function date()
    {
        return GeneralService::getDate($this->created_at);
    }


    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
