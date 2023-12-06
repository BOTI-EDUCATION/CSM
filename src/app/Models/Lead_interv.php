<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead_interv extends Model
{
    use HasFactory;



    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}
