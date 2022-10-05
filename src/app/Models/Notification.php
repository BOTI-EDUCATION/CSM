<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'details',
        'alias',
        'link'
    ];
    

    public function school()
    {
        return $this->belongsTo(School::class,'school_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function addEntry($alias , $school , $label , $details , $link = null ){

        $notification = new Notification();

        $notification->label = $label;
        $notification->alias = $alias;
        $notification->details = $details;
        $notification->link = $link;
        $user = Auth::user();
        $notification->school()->associate($school);
        $notification->user()->associate($user);

        $notification->save();
        
    }
}
