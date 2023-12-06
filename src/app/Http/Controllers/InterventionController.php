<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Depense;
use App\Models\Lead_interv;
use App\Models\Notification;
use App\Models\School;
use App\Models\SchoolIntervention;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{


    public function saveIntervention(Request $request)
    {


        $intervention = new SchoolIntervention();
        $school_id = $request->school_id != "null" ? $request->school_id : $request->school;
        $school = School::find($school_id);

        $responsable = User::find($request->account_manager);
        if (!$responsable)
            $responsable = Auth::user();

        $intervention->school()->associate($school);
        $intervention->responsable()->associate($responsable);

        $intervention->label = $request->label;
        $intervention->details = $request->details;
        $intervention->date = date('Y-m-d H:i:s', strtotime($request->date));
        $intervention->channel = $request->channel;
        $intervention->nature = $request->nature;
        $intervention->type = $request->type;
        $intervention->duration = $request->duration;

        $intervention->state = 'nouveau';
        $intervention->state_history = json_encode([
            ['etat' => 'nouveau', 'date' => date('Y-m-d H:i:s')]
        ]);

        $intervention->save();


        if ($request->hasFile('image')) {
            $filename = $intervention->id . '-' . Helpers::getAlias($intervention->label) . '.' . $request->image->extension();
            $request->image->storeAs('cs_interventions', $filename, 'public');
            $intervention->update(['image' => $filename]);
        }
        
        $interventions = [];

        foreach (Auth::user()->interventions()->get() as $intervention) {
            if ($intervention->school) {
                $interventions[] = [
                    'id' => $intervention->id,
                    'school' => [
                        'id' => $intervention->school->id,
                        'img' => $intervention->school->getLogo(),
                        'name' => $intervention->school->name
                    ],
                    'label' => $intervention->label,
                    'details' => $intervention->details,
                    'date' => $intervention->date
                ];
            }
        }

        Notification::addEntry(
            'intervention',
            $school,
            'Programmation d\'une intervention',
            'Une intervention a été programmée chez ' . $school->label . ' par ' . $responsable->getNomComplet(),
            null
        );




        if ($request->costs) {
            $depense = new Depense();
            $depense->intervention_id = $intervention->id;
            $depense->date = date('Y-m-d H:i:s', strtotime($request->date));
            $depense->user = Auth::user()->nom . ' ' . Auth::user()->prenom;
            $depense->rubriques = implode(',', array_filter($request->rubrique));
            $depense->amounts =  implode(',', array_filter($request->amount));
            $depense->save();
        }

        return response()->json($interventions);
    }




    public function sales_intervention(){
        $interventions = Lead_interv::orderBy('date','DESC')->get();
        
    }

    public function cs_intervention(){
        $interventions = SchoolIntervention::orderBy('date','desc')->get();

        $data = [];

        foreach ($interventions as $key => $item) {

            $school = [];

            if ($item->school) {
                // $school = [
                //     'id' => $item->school->id,
                //     'name' => $item->school->name,
                //     'img' => $item->school->getLogo()
                // ];
                $school =  $item->school->getLogo();
            }

            $data [] = [
                'id' => $item->id,
                'school' => $school,
                'manager' => $item->responsable->getPicture(),
                'label' =>  $item->label,
                'date' => $item->date,
                'detail' => $item->details,
                'duration' => $item->duration,
                'detail' => $item->details,
                'type' => $item->type,
                'nature' => $item->nature,
                'channel' => $item->channel,
                'state' => $item->state,
                'date' => $item->date,
                'image' => $item->image ? asset('src/public/schools/cs_interventions').'/'.$item->image : '',
            ];
        }


        return response()->json($data);
    }
}
