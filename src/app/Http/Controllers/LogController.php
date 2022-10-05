<?php

namespace App\Http\Controllers;

use App\Models\LogError;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{

    public function logError(Request $request){
        $error = new LogError([
            'school' => $request->school_alias,
            'page' => $request->page,
            'error' => $request->error,
            'message' => $request->message,
            'date' => $request->date,
            'handled' => null
        ]);
        
        $error->save();

        return response('success',200);
    }

    public function getErrorsList(){
        $errors = [];

        foreach (LogError::whereNull('Handled')->orderByDesc('date')->get() as $key => $errlog) {
            $school = School::getSchoolByAlias($errlog->school);
            
            if(!$school)
            continue;

            $errors[] = [
                'id' => $errlog->id,
                'school' => [
                    'id'=>$school->id,
                    'logo'=>$school->getLogo(),
                    'name'=>$school->name
                ],
                'page' =>$errlog->page,
                'error' =>$errlog->error,
                'message' =>$errlog->message,
                'date' =>$errlog->date
            ];
        }


        // Notification::addEntry(
        //     'error', 
        //     $school , 
        //     'Programmation d\'une intervention',
        //     'Une intervention a été programmée chez '.$school->label.' par '.$responsable->getNomComplet(),
        //     null
        // );

        return response()->json($errors); 

    }

    public function markHandled(Request $request){

        $error = LogError::find($request->error);

        if(!$error)
        return response('failed',500);

        $error->handled = json_encode([
            'user' => Auth::user()->id,
            'date' => date('Y-m-d H:i:s')
        ]);

        $error->save();
        return response('success',200);

    }
}
