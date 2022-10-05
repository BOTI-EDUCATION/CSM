<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\TrackingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function deleteTrackingSection($id){

        $section = TrackingSection::find($id);

        $section->delete();

        $sections = TrackingSection::all();

        $data = array();

        foreach ($sections as $criteria) {
            $data[] = [
                'id' => $criteria->id,
                'label' => $criteria->label,
                'alias' => $criteria->alias
            ];
        }
        
        return response()->json($data);

    }

    public function getSection($id){
        $section = TrackingSection::find($id);
        return response()->json([
            'id' => $section->id,
            'label' => $section->label
        ]);
    }

    public function saveSection(Request $request){

        if($request->section){
            $section = TrackingSection::find($request->section);
        }else{
            $section = new TrackingSection();
        }
        
        $section->label = $request->label;

        if(!$request->section)
        $section->alias = \App\Helpers::getAlias($request->label);

        $section->save();


        return response()->json('ok');

    }
}
