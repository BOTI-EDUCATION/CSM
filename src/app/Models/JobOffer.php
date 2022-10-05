<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class JobOffer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'job_offer';

    public $timestamps = false;
    protected $fillable = [
        'reference',
        'title',
        'introduction',
        'details',
        'image',
        'infos',
        'city',
        'address',
        'localization',
        'ecole_name',
        'date'
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPicture(){
        if($this->image){
            return asset('src/public/jobOffersImages').'/'.$this->image;
        }
        return asset('src/public/users/no-user.svg');
    }
}
