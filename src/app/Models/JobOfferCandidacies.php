<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class JobOfferCandidacies extends Model
{
    use HasFactory, HasFactory, Notifiable;
    protected $table = 'job_offer_candidacies';

    public $timestamps = false;
    protected $fillable = [
        'job_offer',
        'job_candidate',
        'date_candidacy',
        'views',
        'validation',
        'sent_to_client',
        'files',
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
        if($this->picture){
            return asset('src/public/candidats').'/'.$this->picture;
        }
        return asset('src/public/users/no-user.svg');
    }
}
