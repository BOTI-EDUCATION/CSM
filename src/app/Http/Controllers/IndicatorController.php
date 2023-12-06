<?php

namespace App\Http\Controllers;

use App\Models\Checkup_indicator;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    //

    public function index()
    {
        $indicators = Checkup_indicator::all();
        return response()->json($indicators, 200);
    }


    public function save(Request $request)
    {
        $id = $request->id;
        $indicator =  $id ? Checkup_indicator::find($id) : new Checkup_indicator();
        $indicator->alias =  '7days_'.strtolower($request->label);
        $indicator->label = $request->label;
        $indicator->presentation = $request->presentation;
        $indicator->rubrique = $request->rubrique;
        $indicator->value_type = $request->type;
        $indicator->threshold = $request->thres;
        $indicator->order = $request->order;
        $indicator->save(); 

        return response()->json($indicator,201);
    }


    public function delete($id)
    {
        Checkup_indicator::find($id)->delete();
        return response()->json('Indicator Deleted!');
    }

    public function by_rubrique()
    {
        $indicators = Checkup_indicator::all();
        $rubrique_count = 0 ;
        $rubriques = array();
        $indicator_labels = array();
        $indicator_details = array();

        foreach ($indicators as  $indic) {
           $rubrique_count =  Checkup_indicator::where('rubrique',$indic->rubrique)->count();
           $rubriques[$indic->rubrique] = $rubrique_count;
           $indicator_labels[] = $indic->label;
           $indicator_details[$indic->alias][] = $indic->threshold;
        }

        $returns = [
            'rubriques' => $rubriques,
            'indicator_labels' => $indicator_labels,
            'details' => $indicator_details
        ];

        return response()->json($returns);
    }


  
}
