<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Nature;
use Illuminate\Http\Request;

class NatureController extends Controller
{
    public function index()
    {
        $natures = Nature::get();
        return response()->json($natures,200);
    }

    public function save(Request $request)
    {
        $nature        = $request->id ? Nature::find($request->id) :  new Nature();
        $nature->name  = $request->label;
        $nature->alias = Helpers::getAlias($request->label);
        $nature->save();
        
        return response()->json($nature, 201);
    }


    public function getNature($id)
    {
        $nature = Nature::find($id);
        return response()->json($nature);
    }
    
    public function delete($id)
    {
        Nature::find($id)->delete();
        return response()->json('Nature Deleted Successfully');
    }
}
