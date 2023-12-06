<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'picture',
        'fonction',
        'adresse',
        'enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }

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
            return asset('src/public/users').'/'.$this->picture;
        }
        return asset('src/public/users/no-user.svg');
    }

    public function tickets(){
        return $this->hasMany(SchoolTicket::class,'responsable_id','id');
    }

    public function interventions(){
        return $this->hasMany(SchoolIntervention::class,'responsable_id','id');
    }

    public function getNomComplet(){
        return $this->prenom.' '.$this->nom;
    }

    public static function getAccountManagers(){
        $role = Role::getByAlias('account_manager');

        $users = User::where('role_id','=',$role->id)->get();

        return $users;
    }

    public function notifications(){
        return $this->hasMany(Notification::class,'user_id','id');
    }

    public function getNotifications($count = null){
        if($count){
            
            return $this->notifications()->take($count)->get();
        }else{

            return $this->notifications()->get();
        }
    }

    public function articles() {
        return $this->hasMany(Article::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
