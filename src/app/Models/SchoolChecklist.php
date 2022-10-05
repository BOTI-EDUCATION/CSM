<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolChecklist extends Model
{
    use HasFactory;

    protected $table = 'school_checklist';

    protected $fillable = [
        'done',
        'comment',
        'edit_history'
    ];

    public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }

    public function checklist_item(){
        return $this->belongsTo(ChecklistItem::class,'checklist_item_id','id');
    }
    
}
