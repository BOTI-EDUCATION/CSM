<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\TrackingCriteria;
use App\Models\TrackingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    public function deleteTrackingCriteria($id){

        $criteria = TrackingCriteria::find($id);
        
        $criteria->delete();

        $criterias = TrackingCriteria::all();
        $data = array();

        foreach ($criterias as $criteria) {
            $data[] = [
                'id' => $criteria->id,
                'label' => $criteria->label,
                'short' => $criteria->short,
                'alias' => $criteria->alias,
                'section' => [
                    'id' => $criteria->section->id,
                    'label' => $criteria->section->label
                ]
            ];
        }
        
        return response()->json($data);

    }

    public function getCriteria($id){
        $criteria = TrackingCriteria::find($id);
        return response()->json([
            'id' => $criteria->id,
            'label' => $criteria->label,
            'short' => $criteria->short,
            'section' => $criteria->section->id
        ]);
    }

    public function saveCriteria(Request $request){

        if($request->criteria){
            $criteria = TrackingCriteria::find($request->criteria);
        }else{
            $criteria = new TrackingCriteria();
        }
        
        $section = TrackingSection::find($request->section);

        $criteria->section()->associate($section);

        $criteria->label = $request->label;
        $criteria->short = $request->short;
        
        if(!$request->criteria)
        $criteria->alias = \App\Helpers::getAlias($request->label);

        $criteria->save();


        return response()->json('ok');

    }

    public function getTrackingCriterias(){
        $criterias = TrackingCriteria::all();

        $data = array();

        foreach ($criterias as $criteria) {
            $data[] = [
                'id' => $criteria->id,
                'label' => $criteria->label,
                'short' => $criteria->short,
                'alias' => $criteria->alias,
                'section' => [
                    'id' => $criteria->section->id,
                    'label' => $criteria->section->label
                ]
            ];
        }

        return response()->json($data);

    }
}
