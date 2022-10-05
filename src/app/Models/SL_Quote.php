<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SL_Quote extends Model
{
    use HasFactory;
    protected $table = 'schoollife_quote';
    public $timestamps = true;
    
    protected $fillable = [
        'text',
        'image',
        'Author',
        'function',
        'published',
        'views',
    ];

    public function getPicture(){
        if($this->image){
            return asset('src/public/schoolLife_quotes').'/'.$this->image;
        }
        return asset('src/public/users/no-user.svg');
    }
}
