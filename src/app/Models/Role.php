<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'Label',
        'Alias'
    ];

    public static function getByAlias($alias){
        $role = Role::where('Alias','=',$alias)->first();
        return $role;
    }
}
