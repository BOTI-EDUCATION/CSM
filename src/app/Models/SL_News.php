<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SL_News extends Model
{
    use HasFactory;
    protected $table = 'schoollife_news';
    public $timestamps = true;
    protected $fillable = [
        'label',
        'intro',
        'text',
        'image',
        'link',
        'views',
    ];

    public function getPicture(){
        if($this->image){
            return asset('src/public/schoolLife_news').'/'.$this->image;
        }
        return asset('src/public/users/no-user.svg');
    }
}
