<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackingCriteria extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tracking_criterias';

    protected $fillable = [
        'label',
        'alias',
        'icone',
        'edit_history'
    ];

    public function section(){
        return $this->belongsTo(TrackingSection::class,'section_id','id');
    }
}
