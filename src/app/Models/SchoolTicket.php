<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborateurs',
        'label',
        'details',
        'date',
        'channel',
        'nature',
        'genre',
        'priority',
        'files',
        'state',
        'state_history',
        'edit_history'
    ];

    public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }

    public function responsable(){
        return $this->belongsTo(User::class,'responsable_id','id');
    }

}
