<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Release;
use App\Models\Team;
use App\Models\Type;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    public function index()
    {
        $releases = Release::orderByDesc('date')->with('type')->get();
        $data     = array();

        foreach ($releases as $value) {

            $data[] = [
                'id' => $value->id,
                'label' => $value->label,
                'desc_tech' => $value->desc_tech,
                'desc_csm' => $value->desc_csm,
                'date' => date('D d-m-Y', strtotime($value->date)),
                'status' => $value->status,
                'createdByUser' => json_decode($value->createdByUser),
                'type' => json_decode($value->types),
                'link' => $value->link,
                'image' => $value->image,
            ];
        }

        return response()->json($data, 200);
    }



    public function save(Request $req)
    {
        $id      = $req->id;
        $types   = explode(',', $req->types);
        $members = explode(',', $req->members);
        $id ? $release = Release::find($id) : $release =  new Release();
        $release->label = $req->label;
        $release->desc_tech = $req->desc_tech;
        $release->desc_csm = $req->desc_cs;
        $release->date = $req->date;
        $release->status        = $req->status == "true" ? 1 : 0;
        $release->createdByUser = json_encode($members);
        $release->types         = json_encode($types);
        $release->link          = $req->link;
        $release->save();

        if($req->hasFile('image')){
            $filename = $release->id.'-'.Helpers::getAlias($release->label).'-'.uniqid().'-contents.'.$req->image->extension();
            $req->image->storeAs('contents',$filename,'public');
            $release->update(['image'=>$filename]);
        }

        return response()->json('Inserted Successfully');
    }


    public function delete($id)
    {
        Release::find($id)->delete();
        return response()->json('Deleted Successfully');
    }

    public function getItem($id)
    {
        $item = Release::find($id);
        $data = [
            'id' => $item->id,
            'label' => $item->label,
            'desc_tech' => $item->desc_tech,
            'desc_csm' => $item->desc_csm,
            'date' => date('Y-m-d', strtotime($item->date)),
            'desc_csm' => $item->desc_csm,
            'status' => $item->status,
            'createdByUser' => json_decode($item->createdByUser),
            'type_id' => json_decode($item->types),
            'image' => $item->image,
            'link' => $item->link,
        ];
        return response()->json($data, 200);
    }
}
