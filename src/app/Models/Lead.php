<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'banner',
        'pics',
        'files',
        'effectif',
        'cycles',
        'city',
        'presentation',
        'social_links',
        'adresse',
        'localisation',
        'start_year',
        'first_year_boti',
        'link',
        'effectif_maternelle',
        'effectif_primaire',
        'effectif_college',
        'effectif_lycee',
        'tracking_rank',
        'evaluations',
        'effectif_creche',
        'pricing',
    ];


    public function contacts(){
        return $this->hasMany(LeadContact::class,'lead_id','id');
    }


    public function getLogo(){
        if($this->logo){
            return asset('src/public/leads').'/'.$this->logo;
        }
        return asset('src/public/leads/no-lead-logo.png');
    }
    public function getBanner(){
        if($this->banner){
            return asset('src/public/leads').'/'.$this->banner;
        }
        return asset('src/public/leads/no-lead-logo.png');
    }
    public function getSocialLinks(){
        return json_decode($this->social_links);
    }

    public function getTotalEffectif(){
        $total = 0;
        
        if($this->effectif_creche){
           $total += intval($this->effectif_creche); 
        }
        if($this->effectif_maternelle){
           $total += intval($this->effectif_maternelle); 
        }
        if($this->effectif_primaire){
           $total += intval($this->effectif_primaire); 
        }
        if($this->effectif_college){
           $total += intval($this->effectif_college); 
        }
        if($this->effectif_lycee){
           $total += intval($this->effectif_lycee); 
        }

        return $total;
        
    }

    public function salesManager(){
        return $this->belongsTo(User::class, 'sales_manager','id');
    }
   
}
