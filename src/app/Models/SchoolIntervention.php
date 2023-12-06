<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolIntervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborateurs',
        'label',
        'details',
        'date',
        'duration',
        'type',
        'nature',
        'channel',
        'state',
        'state_history',
        'edit_history',
        'image'
    ];


    public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }

    public function responsable(){
        return $this->belongsTo(User::class,'responsable_id','id');
    }


    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}
