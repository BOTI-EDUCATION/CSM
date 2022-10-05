<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function themesList(){
        $data = array();
        $themes = Theme::all();
        foreach ($themes as $theme) {
            $data[] = [
                'id' => $theme->id,
                'label' => $theme->label,
                'modules' => $theme->getModulesIdLabel(),
            ];
        }
        return response()->json($data);
    }

    public function saveTheme(Request $request){
        
        if($request->theme){
            $theme = Theme::find($request->theme);
        }else{
            $theme = new Theme();
        }
        
        $theme->label = $request->label;
        $theme->alias = \App\Helpers::getAlias($request->label);

        $theme->modules()->sync(explode(',',$request->modules));

        $user = Auth::user();

        $theme->userBy()->associate($user);

        $theme->save();


        return response()->json('ok');
    }

    public function getThemeFormInfo(Request $request , $id){
        $theme = Theme::find($id);
        $data = [
            'id' => $theme->id,
            'label' => $theme->label,
            'modules' => $theme->getModulesIdLabel(),
        ];
        return response()->json($data);
    }

    public function deleteTheme($id){
        $theme = Theme::find($id);
       
        $theme->modules()->detach();
        
        $theme->delete();

        $this->themesList();
    }
}
