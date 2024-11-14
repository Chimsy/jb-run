<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'method_name',
        'params',
        'status',
        'error_message',
    ];
}
