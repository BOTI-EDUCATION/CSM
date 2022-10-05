<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct(){
        
    }

    public function index(){

    }

    public function modulesList(){
        $data = array();
        $modules = Module::all();
        foreach ($modules as $module) {
            $data[] = [
                'id' => $module->id,
                'label' => $module->label,
                'alias' => $module->alias,
            ];
        }
        return response()->json($data);
    }

    public function saveModule(Request $request){
        if($request->module){
            $module = Module::find($request->module);
        }else{
            $module = new Module();
        }
        
        $module->label = $request->label;
        $module->alias = \App\Helpers::getAlias($request->label);

        $user = Auth::user();

        $module->userBy()->associate($user);

        $module->save();


        return response()->json('ok');
    }

    public function getModuleFormInfo(Request $request , $id){
        $module = Module::find($id);
        $data = [
            'id' => $module->id,
            'label' => $module->label,
            'alias' => $module->alias,
        ];
        return response()->json($data);
    }

    public function deleteModule($id){
        $module = Module::find($id);

        $module->delete();

        $this->modulesList();
    }
}
