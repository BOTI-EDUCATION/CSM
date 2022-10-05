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
        'files',
        'comments',
        'likes',
        'keywords',
    ];


    public function getPicture(){
        if($this->image){
            return asset('src/public/schoolLife_articles').'/'.$this->image;
        }
        return asset('src/public/users/no-user.svg');
    }

}
