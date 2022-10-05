<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'function',
        'phone',
        'email',
        'picture',
        'enabled',
        'order',
        'receive_reports',
        'edit_history'
    ];

    public function getPicture(){
        if($this->picture){
            return asset('src/public/leads/contacts').'/'.$this->picture;
        }
        return asset('src/public/users/no-user.svg');
    }

    public function getNomComplet(){
        return $this->last_name.' '.$this->name;
    }

    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id','id');
    }
}
