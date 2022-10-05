<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
    use HasFactory;

    protected $table = 'log_errors';

    protected $fillable = [
        'school',
        'page',
        'error',
        'message',
        'date',
        'handled'
    ];

    public $timestamps = false;
}
