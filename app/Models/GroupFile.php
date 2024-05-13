<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupFile extends Model
{
    use HasFactory;
    protected $table = 'group_files';
    protected $quarde = false;
    protected $guarded = [];
}
