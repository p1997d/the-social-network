<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialogMessage extends Model
{
    use HasFactory;

    protected $table = 'dialog_messages';
    protected $quarde = false;
    protected $guarded = [];
}
