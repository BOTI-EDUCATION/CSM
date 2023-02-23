<?php

namespace App\Http\Controllers;

use App\Models\HelpBloc;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{

    public function TreegetTreeData($id = null)
    {

        $noTypes       = array();
        $british       = array();
        $school        = array();
        $kinder        = array();

        if ($id) {
            $noTypes  = [HelpBloc::find($id)];
            $kinder   = [HelpBloc::find($id)];
            $school   = [HelpBloc::find($id)];
            $british  = [HelpBloc::find($id)];
        } else {
            $noTypes  = HelpBloc::where([['parent_id', '!=', null], ['types', 'LIKE', '%[]%']])->orWhere([['parent_id', '!=', null], ['types', null]])->get();
            $kinder   = HelpBloc::where('types', 'LIKE', '%kinder%')->get();
            $school   = HelpBloc::Where('types', 'LIKE', '%school%')->get();

            // !  BRITISH
            $british  = HelpBloc::where('types', 'LIKE', '%british%')->get();

        }

        $dataSchool   = array();
        $dataBritish  = array();
        $dataKinder   = array();
        $dataNoType   = array();

        foreach ($noTypes as $key => $item) {
            $dataNoType[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand' => false
            ];
        }

        foreach ($school as $key => $item) {
            $dataSchool[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand' => false
            ];
        }

        // ! ABOUT BRITISH
        foreach ($british as $key => $item) {
            $dataBritish[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand' => false
            ];
        }



        foreach ($kinder as $key => $item) {
            $dataKinder[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand' => false
            ];
        }


        return response()->json([
                'school' => $dataSchool, 
                'british' => $dataBritish,
                'kinder' => $dataKinder,
                'noType' => $dataNoType
            ],200);

    }


    public function getTreeDataByTypes($type_1, $type_2, $id = null)
    {

        $dynamicTypes = array();


        if ($id) {
            $dynamicTypes = [HelpBloc::find($id)];
        } else {
            $dynamicTypes  = HelpBloc::where('types', 'LIKE', '%' . $type_1 . '%' . $type_2 . '%')->orWhere('types', 'LIKE', '%' . $type_2 . '%' . $type_1 . '%')->get();
        }

        $dataDynamicTypes  = array();


        foreach ($dynamicTypes as $key => $item) {
            $dataDynamicTypes[] = [
                'id' => $item->id,
                'label' => $item->label,
                'children' => HelpBloc::getTree($item),
                'expand' => false
            ];
        }


        return response()->json(['byTypes' => $dataDynamicTypes],200);
    }





    public function loadDocumentationBritish($id)
    {
        $documentation = HelpBloc::find($id);
        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }

    public function loadDocumentationSchool($id)
    {
        $documentation = HelpBloc::find($id);

        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }


    public function loadDocumentationKinder($id)
    {
        $documentation = HelpBloc::find($id);

        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }


    public function loadDocumentationNoType($id)
    {
        $documentation = HelpBloc::find($id);

        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }


    public function loadDocsByTypes($id)
    {
        $documentation = HelpBloc::find($id);

        $result = [
            'title' => $documentation->label,
            'details' => $documentation->intro,
        ];

        return response()->json($result);
    }



    public function getDocumentation($id)
    {
        $documentation = HelpBloc::find($id);

        $result = [
            'id' => $documentation->id,
            'title' => $documentation->label,
            'details' => $documentation->intro,
            'roles' => $documentation->getRolesAliases(),
            'types' => $documentation->getTypesAliases(),
            'parent' => ($documentation->parent ? [
                'id' => $documentation->parent->id,
                'title' => $documentation->parent->label
            ] : null)
        ];

        return response()->json($result);
    }

    public function saveDocumentation(Request $request)
    {

        if ($request->id) {
            $documentation = HelpBloc::find($request->id);
        } else {
            $documentation = new HelpBloc();
        }

        $documentation->label = $request->title;
        $documentation->intro = $request->details;
        $documentation->types = json_encode($request->types);
        $documentation->roles = json_encode($request->roles);

        if ($request->parent) {
            $parent = HelpBloc::find($request->parent);
            $documentation->parent()->associate($parent);
        }

        $documentation->save();


        return response()->json('ok');
    }


    public function deleteDoc($id)
    {
        HelpBloc::find($id)->delete();
        return response()->json('Deleted Document');
    }

    public function getDocumentationHelp($id = null)
    {

        $elements = array();

        if ($id) {
            $data = array();
            $doc = HelpBloc::find($id);
            $data['doc'] = [
                'id' => $doc->id,
                'label' => $doc->label,
                'details' => $doc->intro,
                'children' => HelpBloc::getDetailsTree($doc),
                'expand' => false
            ];

            $elements = HelpBloc::query()->where('parent_id', '=', null)->get();

            foreach ($elements as $key => $item) {
                $data['documentation'][] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'details' => $item->intro,
                    'children' => HelpBloc::getDetailsTree($item),
                    'expand' => false
                ];
            }

            return response()->json($data);
        } else {

            $elements = HelpBloc::query()->where('parent_id', '=', null)->get();


            $data = array();

            foreach ($elements as $key => $item) {
                $data[] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'details' => $item->intro,
                    'children' => HelpBloc::getDetailsTree($item),
                    'expand' => false
                ];
            }

            return response()->json($data);
        }
    }

    public function searchDocumentationHelp(Request $request)
    {

        $keywords = explode(' ', $request->search);

        $query = HelpBloc::query();

        foreach ($keywords as $word) {
            $query = $query->where('label', 'Like', '%' . $word . '%', 'or');
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
