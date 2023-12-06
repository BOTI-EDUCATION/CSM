<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

class ContentController extends Controller
{
    public function getcontentsList(){

        $data = array();

        $contents = Content::orderBy('created_at','desc')->get();

        foreach($contents as $content){
            $data[] = [
                'id'=> $content->id,
                'type'=> ($content->type?explode(',',$content->type):null),
                'label'=> $content->label,
                'details'=> $content->details,
                'visible'=> $content->visible,
                'image'=> $content->getImage()
            ];
        }

        return response()->json($data);
        
    }


    public function saveContent(Request $request){
        

        if($request->content){
            $content = Content::find($request->content);
        }else{
            $content = new Content();
        }

        $content->label = $request->label;
        $content->type = $request->type;
        $content->details = $request->details;
        $content->visible = $request->visible;
        $content->save();

        // $content->update(['alias'=>Helpers::getAlias($content->id.' '.$request->label)]);

        if($request->hasFile('image')){
            
            $filename = str_shuffle('0123456789_-(-').$content->id.'-'.Helpers::getAlias($content->label).'.'.$request->image->extension();
            $request->image->storeAs('contents',$filename,'public');
            $content->update(['image'=>$filename]);
        }
        

        return response()->json('ok');
    }

    public function getContentFormInfo($id){
        $content = Content::find($id);
        $data = [
            'id'=> $content->id,
            'type'=>($content->type?explode(',',$content->type):null),
            'label'=> $content->label,
            'details'=> $content->details,
            'visible'=> $content->visible,
            'image'=> $content->getImage()
        ];
        return response()->json($data);
    }

    public function deleteContent($id){
        $content = Content::find($id);
        
        $content->delete();
        // return $this->contentsList();
        return response()->json('Deleted Successfully');
    }

    public function changeContentVisibility(Request $request , $id){
        $content = Content::find($id);

        $content->visible = $request->visibility;

        $content->save();

        return $this->getContentFormInfo($id);
    }

    public function getcontentsListToApp(){

        $data = array();

        $contents = Content::orderBy('created_at','desc')->get();

        foreach($contents as $content){
            $data[] = [
                'id'=> $content->id,
                'type'=> ($content->type?explode(',',$content->type):null),
                'label'=> $content->label,
                'details'=> $content->details,
                'visible'=> $content->visible,
                'image'=> $content->getImage()
            ];
        }

        return response()->json($data);
        
    }
}
