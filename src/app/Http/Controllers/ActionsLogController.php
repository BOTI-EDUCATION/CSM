<?php

namespace App\Http\Controllers;

use App\Models\ActionLogs;
use Illuminate\Http\Request;
use App\Helpers;


class ActionsLogController extends Controller
{
    

    public function save_tracking(request $req){
        $newAction = new ActionLogs();
        $newAction->user = $req->user;
        $newAction->date = $req->date;
        $newAction->detail = $req->detail;
        $newAction->object = $req->object;
        $newAction->save();
    }


    public function exports(){

        $actions = ActionLogs::orderBy('date','desc')->get();

        $data = array();
        foreach($actions as $key => $action) {
            $decoded = json_decode($action->object);
            $data[] = [
                'user' => $action->user,
                'detail' => $action->detail,
                'date' => Helpers::dateFormat($action->date, '%A  %d %B %Y %Hh:%Mmin'),
                'type' => $decoded->type,
                'line' => $decoded->lines,
                'school' => $decoded->school,
            ];
        }


        return response()->json($data);
    }
}
