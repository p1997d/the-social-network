<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friends extends Model
{
    use HasFactory;

    protected $table = 'friends';
    protected $quarde = false;
    protected $guarded = [];

}
