<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $table = 'audios';
    protected $quarde = false;
    protected $guarded = [];

    public function audiofile()
    {
        return $this->belongsTo(File::class, 'file');
    }
}
