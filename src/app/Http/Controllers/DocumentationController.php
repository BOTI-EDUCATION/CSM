<?php

namespace App\Http\Controllers;

use App\Models\HelpBloc;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{

    public function getTreeData($id = null){

        $elements = array();

        if($id){
            $elements = [HelpBloc::find($id)];
        }else{

            $elements = HelpBloc::query()->where('parent_id','=',null)->get();
    
        }

        $data = array();

        foreach ($elements as $key => $item) {
            $data[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand'=> false
            ];
        }

        return response()->json($data);

    }

    public function loadDocumentation($id){
        $documentation = HelpBloc::find($id);

        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }

    public function getDocumentation($id){
        $documentation = HelpBloc::find($id);
        
        $result = [
            'id' => $documentation->id,
            'title' => $documentation->label,
            'details' => $documentation->intro,
            'roles' => $documentation->getRolesAliases(),
            'parent' =>($documentation->parent?[
                'id' => $documentation->parent->id,
                'title' => $documentation->parent->label
            ]:null)
        ];

        return response()->json($result);
    }
    
    public function saveDocumentation(Request $request){
        if($request->id){
            $documentation = HelpBloc::find($request->id);
        }else{
            $documentation = new HelpBloc();
        }
        
        $documentation->label = $request->title;
        $documentation->intro = $request->details;
        $documentation->roles = json_encode($request->roles);

        if($request->parent){
            $parent = HelpBloc::find($request->parent);
            $documentation->parent()->associate($parent);
        }

        $documentation->save();


        return response()->json('ok');
    }

    
    public function getDocumentationHelp($id = null){

        $elements = array();

        if($id){
            $data = array();
            $doc = HelpBloc::find($id);
            $data['doc'] = [
                'id' => $doc->id,
                'label' => $doc->label,
                'details' => $doc->intro,
                'children' => HelpBloc::getDetailsTree($doc),
                'expand'=> false
            ];

            $elements = HelpBloc::query()->where('parent_id','=',null)->get();

            foreach ($elements as $key => $item) {
                $data['documentation'][] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'details' => $item->intro,
                    'children' => HelpBloc::getDetailsTree($item),
                    'expand'=> false
                ];
            }

            return response()->json($data);
        }else{

            $elements = HelpBloc::query()->where('parent_id','=',null)->get();
    
            
            $data = array();

            foreach ($elements as $key => $item) {
                $data[] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'details' => $item->intro,
                    'children' => HelpBloc::getDetailsTree($item),
                    'expand'=> false
                ];
            }

            return response()->json($data);
        }

    }

    public function searchDocumentationHelp(Request $request){

        $keywords = explode(' ',$request->search);

        $query = HelpBloc::query();

        foreach ($keywords as $word) {
            $query = $query->where('label','Like','%'.$word.'%','or');
        }

        $result = [];
        foreach ($query->get() as $key => $value) {
            $result[] = [
                'id' => $value->id,
                'label' => $value->label,
                'details' => $value->intro
            ];
        }

        return response()->json($result);

    }
}
