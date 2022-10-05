<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'alias',
    ];

    public function modules(){
        return $this->belongsToMany(Module::class,'module_themes','theme_id','module_id');
    }

    public function getModulesIds(){
        $ids = array();
        foreach ($this->modules()->get() as $module) {
            $ids[] = $module->id;
        }
        return $ids;
    }
    
    public function getModulesIdLabel(){
        $ids = array();
        foreach ($this->modules()->get() as $module) {
            $ids[] = [
                'id'=>$module->id,
                'label'=>$module->label
            ];
        }
        return $ids;
    }

    public function userBy(){
        return $this->belongsTo(User::class,'user_by','id');
    }
}
