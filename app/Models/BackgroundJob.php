<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundJob extends Model
{

    protected $fillable = [
        'class_name',
        'method_name',
        'params',
        'status',
        'error_message',
        'retry_count',
        'is_running',
    ];
}
