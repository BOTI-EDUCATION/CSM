<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'alias',
        'icon',
        'presentation',
        'required',
        'edit_history',
    ];

    public function items(){
        return $this->hasMany(ChecklistItem::class,'checklist','id');
    }
    
    public function getPourcentage($school){

        $countItems = $this->items()->count();

        if(!$countItems)
        return 0 ;

        $checkedCount = 0;
        foreach ($this->items()->get() as $item) {
           $schoolCheckItem = $school->school_checklist_item()->where('checklist_item_id',$item->id)->first();

           if($schoolCheckItem && $schoolCheckItem->done){
               $checkedCount++;
           }
        }

        return round((($checkedCount*100/$countItems)),2);
    }
}
