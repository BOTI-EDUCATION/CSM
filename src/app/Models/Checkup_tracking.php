<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkup_tracking extends Model
{
    use HasFactory;

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
