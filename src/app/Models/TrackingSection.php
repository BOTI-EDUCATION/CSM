<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackingSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tracking_sections';

    protected $fillable = [
        'label',
        'alias',
        'icone',
        'edit_history'
    ];

    public function criterias(){
        return $this->hasMany(TrackingCriteria::class,'section_id','id');
    }
}
