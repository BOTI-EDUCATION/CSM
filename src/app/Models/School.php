<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory,SoftDeletes;

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
        'checklists',
        'deleted_at'
    ];

    
    public function accountManager(){
        return $this->belongsTo(User::class,'account_manager','id');
    }

    public function contacts(){
        return $this->hasMany(SchoolContact::class,'school_id','id');
    }

    public function interventions(){
        return $this->hasMany(SchoolIntervention::class,'school_id','id');
    }

    public function tickets(){
        return $this->hasMany(SchoolTicket::class,'school_id','id');
    }

    public function getLogo(){
        if($this->logo){
            return asset('src/public/schools').'/'.$this->logo;
        }
        return asset('src/public/schools/no-school-logo.png');
    }
    public function getBanner(){
        if($this->banner){
            return asset('src/public/schools').'/'.$this->banner;
        }
        return asset('src/public/schools/no-school-logo.png');
    }
    public function getSocialLinks(){
        return json_decode($this->social_links);
    }
    public function metrics(){
        return $this->hasMany(TrackingMetric::class,'school_id','id');
    }

    public function getMetricsScore(){
        $total = 0;
        $criterias = TrackingCriteria::all();

            $lastCriterias = array();

            foreach ($criterias as $criteria) {
                $enregistrement = $this->metrics()->where('criteria_id',$criteria->id)->orderBy('created_at','desc')->first();
                if($enregistrement)
                $total += intval($enregistrement->value);
            }

            return $total;
    }

    public function getChecklists(){
        if(!$this->checklists)
        return [];

        $checklists = array();

        foreach (json_decode($this->checklists) as $id) {
            $checklist = Checklist::find($id);
            if($checklist){
                $checklists[] = $checklist;
            }else{
                continue;
            }
        }

        return $checklists;
    }

    public function school_checklist_item(){
        return $this->hasMany(SchoolChecklist::class,'school_id','id');
    }

    public function checklists_items(){
        return $this->belongsToMany(ChecklistItem::class,'school_checklist','school_id','checklist_item_id');
    }

    public function getChecklistItems($checklist){
        $items = $this->checklists_items()->where('checklist',$checklist->id)->get();
        return $items;
    }

    public static function getSchoolByAlias($alias){
        $school = School::where('web_link','LIKE','%/'.$alias)->first();
        return $school;
    }

}
