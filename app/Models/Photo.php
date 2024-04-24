<?php

namespace App\Models;

use App\Services\GeneralService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';
    protected $quarde = false;
    protected $guarded = [];

    public function date()
    {
        return GeneralService::getDate($this->created_at);
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
