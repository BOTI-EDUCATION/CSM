<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'checklist_items';

    protected $fillable = [
        'label',
        'alias',
        'ordre',
        'edit_history',
    ];

    public function getChecklist(){
        return $this->belongsTo(Checklist::class,'checklist','id');
    }
}
