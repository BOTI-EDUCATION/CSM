<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostViews extends Model
{
    use HasFactory;

    protected $table = 'posts_vues';

    protected $fillable = [
        'client',
        'source',
        'device',
        'navigateur'
    ];
}
