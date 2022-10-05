<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Notification;
use App\Models\School;
use App\Models\SchoolIntervention;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    

    public function saveIntervention(Request $request){

        $intervention = new SchoolIntervention();

        $school = School::find($request->school);

        $responsable = User::find($request->account_manager);
        if(!$responsable)
        $responsable = Auth::user();

        $intervention->school()->associate($school);
        $intervention->responsable()->associate($responsable);

        $intervention->label = $request->label;
        $intervention->details = $request->details;
        $intervention->date = date('Y-m-d H:i:s',strtotime($request->date));
        $intervention->channel = $request->channel;
        $intervention->nature = $request->nature;
        $intervention->type = $request->type;
        $intervention->duration = $request->duration;

        $intervention->state = 'nouveau';
        $intervention->state_history = json_encode([  
            ['etat'=>'nouveau','date'=>date('Y-m-d H:i:s')]
        ]);

        $intervention->save();

        $interventions = [];

        foreach (Auth::user()->interventions()->get() as $intervention) {
            if($intervention->school){
                $interventions[] = [
                'id' => $intervention->id,
                'school' => [
                    'id'=>$intervention->school->id,
                    'img'=>$intervention->school->getLogo(),
                    'name'=>$intervention->school->name
                ],
                'label' => $intervention->label,
                'details' => $intervention->details,
                'date' => $intervention->date
            ];
        }
    }

        Notification::addEntry(
            'intervention', 
            $school , 
            'Programmation d\'une intervention',
            'Une intervention a été programmée chez '.$school->label.' par '.$responsable->getNomComplet(),
            null
        );
        
        return response()->json($interventions); 

    }
}
