<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    
    public function getConfigsList(){
        $data = array();
        $configs = Config::all();
        foreach ($configs as $config) {
            $data[] = [
                'id' => $config->id,
                'label' => $config->label,
                'alias' => $config->alias,
                'value' => $config->value,
            ];
        }
        return response()->json($data);
    }

    public function updateConfig(Request $request){
        $config = Config::find($request->id);

        if(!$config)
        return response('Config not Found !',404);

        $config->value = $request->value;
        $config->save();

        return response()->json('ok');

    }
}
