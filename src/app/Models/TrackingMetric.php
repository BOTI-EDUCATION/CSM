<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingMetric extends Model
{
    use HasFactory;

    protected $table = 'tracking_metrics';

    protected $fillable = [
        'value',
        'meta'
    ];

    public function criteria(){
        return $this->belongsTo(TrackingCriteria::class,'criteria_id','id');
    }

    public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }
}
