<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'alias',
        'details',
        'image',
        'enabled',
        'views',
        'date_publication'
    ];

    public function views(){
        return $this->hasMany(PostViews::class,'post_id','id');
    }

    public function getImage(){
        if(!$this->image)
        return null;

        return (asset('src/public/posts/'.$this->image));
    }
}
