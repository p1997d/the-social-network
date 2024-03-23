<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EducationEnum;
use App\Enums\FamilyStatusEnum;

class Info extends Model
{
    use HasFactory;

    protected $table = 'user_info';
    protected $quarde = false;
    protected $guarded = [];

    protected $casts = [
        'education' => EducationEnum::class,
        'family_status' => FamilyStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function avatarFile()
    {
        return $this->hasOne(File::class, 'id', 'avatar');
    }
}
