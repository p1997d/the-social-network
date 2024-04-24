<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\GeneralService;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    protected $quarde = false;
    protected $guarded = [];

    public function viewsWithText() {
        return GeneralService::getPluralize($this->views, 'просмотр');
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
