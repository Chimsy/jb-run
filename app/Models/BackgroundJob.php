<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class BackgroundJob extends Model
{

    protected $fillable = [
        'class_name',
        'method_name',
        'params',
        'priority',
        'status',
        'error_message',
        'retry_count',
        'is_running',
    ];
}
