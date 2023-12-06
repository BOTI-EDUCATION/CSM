<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = 'articles';
    public $timestamps = false;
    protected $fillable = [
        'label',
        'introduction',
        'details',
        'blogs',
        'keys',
        'image',
        'video',
        'files',
        'comments',
        'likes',
        'date_publication',
        'visible',
        'keywords',
        'user_id',
        'category_id'
    ];


    public function getPicture()
    {
        if ($this->image) {
            return asset('src/public/schoolLife_articles') . '/' . $this->image;
        }
        return asset('src/public/users/no-user.svg');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(articleCategory::class, 'category_id');
    }
}
