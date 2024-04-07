<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    protected $quarde = false;
    protected $guarded = [];

    public function videoFile()
    {
        return $this->belongsTo(File::class, 'file');
    }

    public function viewsWithText() {
        return GeneralService::getPluralize($this->views, 'просмотр');
    }
}
