<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Education;
use App\Enums\FamilyStatus;

class Info extends Model
{
    use HasFactory;

    protected $table = 'user_info';
    protected $quarde = false;
    protected $guarded = [];

    protected $casts = [
        'education' => Education::class,
        'family_status' => FamilyStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
