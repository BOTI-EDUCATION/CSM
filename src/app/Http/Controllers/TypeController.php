<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    public function index()
    {
        $types = Type::all();
        return response()->json($types, 200);
    }


    public function saveType(Request $request)
    {
        $id         = $request->id;
        $id ? $type = Type::find($id) : $type = new Type();
        $type->name = $request->label;
        $type->type_alias = Helpers::getAlias($request->label);
        $type->save();

        return response()->json($type, 201);
    }

    public function deleteType($id)
    {
        Type::find($id)->delete();
        return response()->json("Deleted Successfully");
    }

    public function getItem($id)
    {
        $item = Type::find($id);
        return response()->json($item, 200);
    }
}
