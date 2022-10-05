<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\TrackingCriteria;
use App\Models\TrackingMetric;
use App\Models\TrackingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{

    public function getTrackingSections(){

        $data = array();

        $sections = TrackingSection::all();

        foreach ($sections as $section) {
            $criterias = array();
            foreach ($section->criterias()->get() as $criteria) {
                $criterias[] = [
                    'id' => $criteria->id,
                    'label' => $criteria->label,
                    'alias' => $criteria->alias,
                    'short' => $criteria->short,
                    'checked' => false
                ];
            }
            $data[] = [
                'id' => $section->id,
                'label' => $section->label,
                'alias' => $section->alias,
                'criterias' => $criterias,
                'checked' => true
            ];
        }

        return response()->json($data);

    }

    public function saveTrackingMatrix(Request $request){


        $matrice = json_decode($request->matrice);
        
        foreach ($matrice as $index => $value) {
            $ids = explode('_',$index);

            $school = School::find($ids[0]);
            $section = TrackingSection::find($ids[1]);
            $criteria = null;

            if(isset($ids[2]) && $ids[2]){
                $criteria = TrackingCriteria::find($ids[2]);
            }
            
            if($criteria){
                $metrics = new TrackingMetric();
    
                $metrics->school()->associate($school);
                $metrics->criteria()->associate($criteria);

                $metrics->value = $value;

                $user = Auth::user();

                $metrics->meta = json_encode([
                    'label' => $request->label,
                    'date' => date('Y-m-d H:i:s',strtotime($request->date)),
                    'user_name' => $user->getNomComplet(),
                    'user_id' => $user->id
                ]);

                $metrics->save();

            }


        }

        return response()->json('ok');

    }

    public function getTrackingMatrice(){
        $data = array();

        $schools = School::all();
        $criterias = TrackingCriteria::all();

        foreach ($schools as $school) {
            $lastCriterias = array();

            foreach ($criterias as $criteria) {
                $enregistrement = $school->metrics()->where('criteria_id',$criteria->id)->orderBy('created_at','desc')->first();
                $lastCriterias[$criteria->id] = ($enregistrement?$enregistrement->value:'-'); 
            }

            $data[] = [
                'id' => $school->id,
                'name' => $school->name,
                'effectif' => $school->effectif,
                'cycles' => explode(',',$school->cycles),
                'city' => $school->city,
                'presentation' => $school->presentation,
                'adresse' => $school->adresse,
                'link' => $school->link,
                'logo' => $school->getLogo(),
                'criterias' => $lastCriterias,
                'metricScore' => $school->getMetricsScore()
            ];
        }

        return response()->json($data);
        
    }

}
