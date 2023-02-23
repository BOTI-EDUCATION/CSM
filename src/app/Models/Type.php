<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
                    'name',
                    'type_alias'
               ];

            

    public function releases()
    {
        return $this->hasMany(Release::class);
    }
}
