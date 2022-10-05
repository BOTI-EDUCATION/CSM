<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\School;
use App\Models\SchoolChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function __construct(){
        
    }

    public function index(){

    }

    public function checklistsList(){
        $data = array();
        $checklists = Checklist::all();
        foreach ($checklists as $checklist) {
            $data[] = [
                'id' => $checklist->id,
                'label' => $checklist->label,
                'alias' => $checklist->alias,
                'presentation' => $checklist->presentation,
                'required' => $checklist->required,
            ];
        }
        return response()->json($data);
    }

    public function getChecklists(){
        $data = array();
        $checklists = Checklist::all();
        foreach ($checklists as $checklist) {
            $items = [];

            foreach ($checklist->items()->orderBy('ordre')->get() as $itm) {
                $items[] = [
                    'id' => $itm->id,
                    'label' => $itm->label,
                ];
            }

            $data[] = [
                'id' => $checklist->id,
                'label' => $checklist->label,
                'presentation' => $checklist->presentation,
                'items' => $items
            ];
        }
        return response()->json($data);
    }

    public function saveChecklist(Request $request){
        if($request->checklist){
            $checklist = Checklist::find($request->checklist);
        }else{
            $checklist = new Checklist();
        }
        
        $checklist->label = $request->label;
        $checklist->alias = \App\Helpers::getAlias($request->label);
        $checklist->icon = $request->icone;
        $checklist->presentation = $request->presentation;
        $checklist->required = ($request->required?1:0);
        
        $checklist->save();
        
        $questions = ($request->questions?json_decode($request->questions):null);
        
        if($questions){
    
            $itemsIds = $checklist->items()->pluck('id')->toArray();
            // dd($itemsIds);
            // exit;
            foreach ($questions as $key => $question) {

                if($itemsIds&&in_array($question->item,$itemsIds)){
                    unset($itemsIds[array_search($question->item,$itemsIds)]);
                }

                if($question->item){
                    $item = ChecklistItem::find($question->item);
                    $item->label = $question->label;
                    $item->alias = \App\Helpers::getAlias($question->label);
                    $item->ordre = $key;
                    
                    $item->getChecklist()->associate($checklist);
                    $item->save();
                    
                }else{
                    $item = new ChecklistItem();
                    $item->label = $question->label;
                    $item->alias = \App\Helpers::getAlias($question->label);
                    $item->ordre = $key;
                    
                    $item->getChecklist()->associate($checklist);
                    $item->save();
                }
            }
        }

        foreach ($itemsIds as $noID) {
            $nonExist = ChecklistItem::find($noID);
            $nonExist->delete();
        }

        return response()->json('ok');
    }

    public function getChecklistFormInfo(Request $request , $id){
        $checklist = Checklist::find($id);
        $items = $checklist->items()->orderBy('ordre')->get();
        $questions = [];

        foreach ($items as $key => $item) {
            $questions[] = [ 'id' => $key , 'label' => $item->label , 'item' => $item->id ];
        }

        $data = [
            'id' => $checklist->id,
            'label' => $checklist->label,
            'alias' => $checklist->alias,
            'icone' => $checklist->icon,
            'presentation' => $checklist->presentation,
            'required' => $checklist->required,
            'questions' => $questions,
        ];
        return response()->json($data);
    }

    public function deleteChecklist($id){
        $checklist = Checklist::find($id);

        $checklist->delete();

        $this->checklistsList();
    }

    public function getCheckupMatrix(Request $request)
    {
        // dd($request->selected_types);
        // exit;
        
        $schools = [];
        $schools_checklists = [];
        
        if($request->selected_types){
            $query = null;

            foreach ($request->selected_types as $type) {
                if($query){
                    $query = $query->orWhere('types','LIKE','%'.$type.'%');
                }else{
                    $query = School::orWhere('types','LIKE','%'.$type.'%');
                }
            }
            
            if($query){
                
                $schools = $query->get();
            }else
            $schools = School::all();


        }else{
            $schools = School::all();
        }

        $data = [];
        
        foreach (SchoolChecklist::all() as $key => $value) {
            
            $data['values'][$value->school_id.'_'.$value->checklist_item_id] = ($value->done?true:false);
        }

        foreach ($schools as $school) {
            $data['schools'][] = [
                'id' => $school->id,
                'name' => $school->name,
                'effectif' => $school->effectif,
                'cycles' => explode(',',$school->cycles),
                'types' => explode(',',$school->types),
                'city' => $school->city,
                'presentation' => $school->presentation,
                'adresse' => $school->adresse,
                'localisation' => $school->localisation,
                'link' => $school->link,
                'logo' => $school->getLogo(),
            ];
        }
        return response()->json($data);

    }
}
