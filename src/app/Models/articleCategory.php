<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articleCategory extends Model
{
    use HasFactory;
    protected $table = 'article_category';

    public $timestamps = false;

    protected $fillable = [
        'label',
        'alias',
        'icon',
        'hasVideo',
        'specific_date'
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
