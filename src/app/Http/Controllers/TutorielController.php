<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Module;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Http\Request;

class TutorielController extends Controller
{
    public function gettutorielsList(){

        $data = array();

        $tutoriels = Video::all();

        foreach($tutoriels as $tutoriel){
            $data[] = [
                'id'=> $tutoriel->id,
                'label'=> $tutoriel->title,
                'details'=> $tutoriel->presentation,
                'youtube_id' => $tutoriel->youtube_id,
                'image'=> $tutoriel->getImage(),
                'views'=> 0,
            ];
        }

        return response()->json($data);
        
    }



    public function saveTutoriel(Request $request){
        

        if($request->id){
            $tutoriel = Video::find($request->id);
        }else{
            $tutoriel = new Video();
        }


        $tutoriel->title = $request->label;
        $tutoriel->youtube_id = $request->youtubeID;
        $tutoriel->presentation = $request->details;

        $tutoriel->save();

        $tutoriel->themes()->sync($request->themes);

        return response()->json('ok');
    }

    public function getTutorielFormInfo(Request $request , $id){
        $tutoriel = Video::find($id);
        $data = [
            'id'=> $tutoriel->id,
            'label'=> $tutoriel->title,
            'details'=> $tutoriel->alias,
            'youtubeID'=> $tutoriel->youtube_id,
            'video'=> $tutoriel->getLink(),
        ];
        return response()->json($data);
    }

    public function deletePost($id){
        $tutoriel = Post::find($id);
        
        $tutoriel->delete();

        $this->tutorielsList();
    }
   
    public function getTutorielModules(){
        $modules = Module::all();
        $result = [];
        foreach ($modules as $module) {
            $themes = [];
            foreach ($module->themes as $theme) {
                $themes[] = [
                    'id' => $theme->id,
                    'label' => $theme->label
                ];
            }

            $result[] = [
                'id' => $module->id,
                'label' => $module->label,
                'themes' => $themes
            ];
        }

        return response()->json($result);
    }
}
