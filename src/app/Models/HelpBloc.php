<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TextUI\Help;

class HelpBloc extends Model
{
    use HasFactory;

    protected $table = 'helpblocs';

    protected $fillable = [
        'label',
        'intro',
        'ordre',
        'visible'
    ];

    public static function getTree($helpbloc=null) {
        
        
        // $helpblocsObjs = HelpBloc::getList(array('where' => $where ,'order' => array('Ordre' => true)), $query);
        $helpblocsObjs = HelpBloc::query()->where('parent_id','=',($helpbloc?$helpbloc->id:null))->get();

        $helpblocs = array();
        foreach ($helpblocsObjs as $item) {
            $helpbloc = array();
            $helpbloc['id'] = $item->id;
            $helpbloc['label'] = $item->label;
            $helpbloc['children'] = HelpBloc::getTree($item);
            $helpbloc['expand'] = false;
            $helpblocs[] = $helpbloc;
        }
        return $helpblocs;
    }

    public static function getDetailsTree($helpbloc=null) {
        
        
        // $helpblocsObjs = HelpBloc::getList(array('where' => $where ,'order' => array('Ordre' => true)), $query);
        $helpblocsObjs = HelpBloc::query()->where('parent_id','=',($helpbloc?$helpbloc->id:null))->get();

        $helpblocs = array();
        foreach ($helpblocsObjs as $item) {
            $helpbloc = array();
            $helpbloc['id'] = $item->id;
            $helpbloc['label'] = $item->label;
            $helpbloc['details'] = $item->intro;
            $helpbloc['children'] = HelpBloc::getTree($item);
            $helpbloc['expand'] = false;
            $helpblocs[] = $helpbloc;
        }
        return $helpblocs;
    }

    // public static function getTree($helpbloc=null) {
    //     return helpblocsNestFunction($helpbloc);
    // }

    public function parent() {
        return $this->belongsTo(HelpBloc::class,'parent_id','id');
    }
        
    public function children() {
        return $this->hasMany(HelpBloc::class,'parent_id','id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class , 'helpblocrole', 'helpbloc_id' , 'role_id');
    }

    public function roleIds(){
        return $this->roles()->get()->modelKeys();
    }
    
    public function getRolesAliases(){
        return ($this->roles?json_decode($this->roles):[]);
    }

        
}
