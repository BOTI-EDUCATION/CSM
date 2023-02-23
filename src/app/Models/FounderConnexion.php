<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FounderConnexion extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'user_agent',
        'device',
        'ip',
        'school_alias',
        'navigateur',
    ];
}
