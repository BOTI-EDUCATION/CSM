<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;

    
    protected $fillable = ['label', 'desc_tech', 'desc_csm', 'date', 'status', 'createdByUser', 'type_id', 'link', 'image'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
