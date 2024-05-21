<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $quarde = false;
    protected $guarded = [];

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function createdAtDiffForHumans()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function createdAtIsoFormat()
    {
        return Carbon::parse($this->created_at)->isoFormat('LL LTS');
    }
}
