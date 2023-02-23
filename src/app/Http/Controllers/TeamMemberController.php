<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
     
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams, 200);
    }


    public function saveTeam(Request $request)
    {
        $id             = $request->id;
        $id ? $type     = Team::find($id) : $type = new Team();
        $type->fullname = $request->fullname;
        $type->fonction = $request->fonction;
        $type->save();

        if($request->hasFile('image')){
            $filename = $type->id.'-'.$type->fullname.uniqid().'-contents.'.$request->image->extension();
            $request->image->storeAs('contents',$filename,'public');
            $type->update(['avatar'=>$filename]);
        }

        return response()->json($type, 201);
    }

    public function deleteMember($id)
    {
        Team::find($id)->delete();
        return response()->json("Deleted Successfully");
    }

    public function getItem($id)
    {
        $item = Team::find($id);
        return response()->json($item, 200);
    }
}
