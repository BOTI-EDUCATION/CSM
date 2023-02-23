<?php

namespace App\Http\Controllers;

use App\Models\FounderConnexion;
use Illuminate\Http\Request;

class FounderConxController extends Controller
{
    public function save(Request $request)
    {
        $new_log               = new FounderConnexion();
        $new_log->username     = $request->username;
        $new_log->school_alias = $request->school_alias;
        $new_log->user_agent   = $request->user_agent;
        $new_log->ip           = $request->IP;
        $new_log->device       = $request->device;
        $new_log->navigateur   = 'mobile';
        $new_log->save();
        return $new_log;

    }

    public function index()
    {
        $logs = FounderConnexion::orderBy('id', 'DESC')->get();
        $data = [];
        foreach ($logs as $value) {
            $data[] = [
                'id' => $value->id,
                'username' => $value->username,
                'user_agent' => $value->user_agent,
                'ip' => $value->ip,
                'school_alias' => $value->school_alias,
                'device' => $value->device,
                'navigateur' => $value->navigateur,
                'date' => date('d M Y h:i', strtotime($value->created_at)).'min',
            ];
        }
        return response()->json($data,200);
    }
}
