<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Notification;
use App\Models\SchoolIntervention;
use App\Models\SchoolTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function getNotifList(){

        $i = 1;

        $data = array();

        $interventions = SchoolIntervention::all();
        $tickets = SchoolTicket::all();

        foreach ($interventions as $key => $item) {
            if($item->school){
                $data[] = [
                'id' => $i,
                'label' => $item->label,
                'details' => $item->details,
                'school' => [
                    'id' => $item->school->id,
                    'label' => $item->school->label,
                    'img' => $item->school->getLogo()
                ],
                'collaborateur' => [
                    'id' => $item->responsable->id,
                    'label' => $item->responsable->getNomComplet(),
                    'img' => $item->responsable->getPicture()
                ],
                'date' => date('l d M Y',strtotime($item->date)),
                'time' => date('H:i',strtotime($item->date)),
                'date_brut' => $item->date
            ];

            $i++;
                
        }
        }

        foreach ($tickets as $key => $item) {
            if($item->school){
                $data[] = [
                'id' => $i,
                'label' => $item->label,
                'details' => $item->details,
                'dure'    => substr($item->dure, 0,2) == '00' ? substr($item->dure,3,2).'min': substr($item->dure, 0,2).'H'.substr($item->dure,3,2).'min',
                'status' => $item->status,
                'school' => [
                    'id' => $item->school->id,
                    'label' => $item->school->label,
                    'img' => $item->school->getLogo()
                ],
                'collaborateur' => [
                    'id' => $item->responsable->id,
                    'label' => $item->responsable->getNomComplet(),
                    'img' => $item->responsable->getPicture()
                ],
                'date' => date('l d M Y',strtotime($item->date)),
                'time' => date('H:i',strtotime($item->date)),
                'date_brut' => $item->date
            ];

            $i++;
            }
        }
        
        $sorted_data = Helpers::sort_array_multidim($data,"date_brut DESC");
        

        return response()->json($sorted_data);
    }

    function getLastNotifications(){
        $user = Auth::user();
        $notifications = $user->getNotifications(50);
        $data = array();

        foreach ($notifications as $notif) {
            $school = null;
            if($notif->school){
                $school = [
                    'id' => $notif->school->id,
                    'name' => $notif->school->name,
                    'img' => $notif->school->getLogo()
                ];
            }

            $data[] = [
                'label' => $notif->label,
                'details' => $notif->details,
                'type' => $notif->alias,
                'date' => \App\Helpers::dateFormat($notif->created_at),
                'school' => $school
            ];
        }

        return response()->json($data);
    }
}
