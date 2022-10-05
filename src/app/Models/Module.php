<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'alias',
        'icone'
    ];

    public function userBy(){
        return $this->belongsTo(User::class,'user_by','id');
    }

    public function themes(){
        return $this->belongsToMany(Theme::class,'module_themes','module_id','theme_id');
    }
}
