<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackgroundJob extends Model
{
    protected $fillable = ['class', 'method', 'parameters', 'status', 'attempts', 'error_message'];
    protected $casts = [
        'parameters' => 'array',
    ];
}
