<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Http\Controllers\Paramettrage\UserController;
use App\Models\Notification;
use App\Models\School;
use App\Models\SchoolTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
    }

    public function saveTicket(Request $request)
    {


        if (isset($request->id) && $request->id) {
            $ticket = SchoolTicket::find($request->id);
        } else {
            $ticket = new SchoolTicket();
        }

        // dd($ticket);

        $school_id = $request->school_id != "null" && !$request->school ? $request->school_id : $request->school;

        $school = School::find($school_id);
        $responsable = Auth::user();

        $ticket->school()->associate($school);
        $ticket->responsable()->associate($responsable);

        $ticket->label = $request->label;
        $ticket->details = $request->details;
        $ticket->date = date('Y-m-d H:i:s');
        $ticket->channel = $request->channel;
        $ticket->nature = $request->nature;
        $ticket->genre = $request->genre;
        $ticket->priority = $request->priority;
        $ticket->contact_id = $request->contact_id;
        $ticket->responsable_id = $request->responsable_id;
        $ticket->dure = $request->duree;
        $ticket->hour = $request->hour;
        $ticket->status = $request->status;

        $ticket->state = 'nouveau';
        $ticket->state_history = json_encode([
            ['etat' => 'nouveau', 'date' => date('Y-m-d H:i:s')]
        ]);

        $ticket->save();

        if ($request->hasFile('pieces')) {
            $i = 1;
            $filesArray = [];
            foreach ($request->pieces as $file) {
                $filename = $ticket->id . '-' . Helpers::getAlias($ticket->label) . '-' . $i . '.' . $file->extension();
                $file->storeAs('tickets', $filename, 'public');
                $filesArray[] = $filename;
                ++$i;
            }
            $ticket->update(['files' => json_encode($filesArray)]);
        }

        $tickets = [];

        foreach ($responsable->tickets()->has('school')->get() as $ticket) {
            $tickets[] = [
                'id' => $ticket->id,
                'school' => [
                    'id' => $ticket->school->id,
                    'img' => $ticket->school->getLogo(),
                    'name' => $ticket->school->name
                ],
                'label' => $ticket->label,
                'details' => $ticket->details,
                'date' => $ticket->date
            ];
        }

        if ($school) {
            Notification::addEntry(
                'ticket',
                $school,
                'Ajout d\'un ticket',
                'Une ticket a été pour ' . $school->label . ' par ' . $responsable->getNomComplet()
            );
        }

        return response()->json($tickets);
    }


    public function deleteTicket($id)
    {
        SchoolTicket::find($id)->delete();
        return response()->json('Deleted');
    }

    public function showTicket($id)
    {
        $ticket = SchoolTicket::find($id);
    }


    public function getTickets()
    {
        $user = Auth::user();
        $tickets = [];

        foreach (SchoolTicket::orderBy('created_at','desc')->get() as $ticket) {
            $school = null;
            if ($ticket->school) {
                $school = [
                    'id' => $ticket->school->id,
                    'name' => $ticket->school->name,
                    'img' => $ticket->school->getLogo()
                ];
            }

            $user = [
                'img' => $ticket->responsable->getPicture(),
                'name' => $ticket->responsable->getNomComplet(),
                'id' => $ticket->responsable->id,
            ];

            $infos = [
                'channel' => [
                    'color' => 'primary',
                    'label' => $ticket->channel
                ],
                'nature' => [
                    'color' => 'primary',
                    'label' => $ticket->nature
                ],
                'genre' => [
                    'color' => 'primary',
                    'label' => $ticket->genre
                ],
                'priority' => [
                    'color' => 'primary',
                    'label' => $ticket->priority
                ],
                'state' => [
                    'color' => 'primary',
                    'alias' => $ticket->state,
                    'label' => $ticket->state
                ],
            ];

            $tickets[] = [
                'id' => $ticket->id,
                'school' => $school,
                'responsable' => $user,
                'label' => $ticket->label,
                'details' => $ticket->details,
                'date' => $ticket->date,
                'infos' => $infos
            ];
        }

        return $tickets;
    }


    public function filters_ticket(Request $request)
    {

        $tickets = [];

        $results = SchoolTicket::where([
            $request->status && $request->status != 'status' ? ['state', $request->status] : ['state', '!=', null],
            $request->school && $request->school != 'school' ?  ['school_id', $request->school] : ['school_id', '!=', null],
            $request->responsable && $request->responsable != 'responsable' ? ['responsable_id', $request->responsable] : ['responsable_id', '!=', null]
        ])->get();


        foreach ($results as $ticket) {
            $school = null;
            if ($ticket->school) {
                $school = [
                    'id' => $ticket->school->id,
                    'name' => $ticket->school->name,
                    'img' => $ticket->school->getLogo()
                ];
            }

            $user = [
                'id' => $ticket->responsable->id,
                'img' => $ticket->responsable->getPicture(),
                'name' => $ticket->responsable->getNomComplet()
            ];

            $infos = [
                'channel' => [
                    'color' => 'primary',
                    'label' => $ticket->channel
                ],
                'nature' => [
                    'color' => 'primary',
                    'label' => $ticket->nature
                ],
                'genre' => [
                    'color' => 'primary',
                    'label' => $ticket->genre
                ],
                'priority' => [
                    'color' => 'primary',
                    'label' => $ticket->priority
                ],
                'state' => [
                    'color' => 'primary',
                    'alias' => $ticket->state,
                    'label' => $ticket->state
                ],
            ];

            $tickets[] = [
                'id' => $ticket->id,
                'school' => $school,
                'responsable' => $user,
                'label' => $ticket->label,
                'details' => $ticket->details,
                'date' => $ticket->date,
                'infos' => $infos
            ];
        }

        return response()->json($tickets);
    }




    public function updateTicketState(Request $request)
    {

        $ticket = SchoolTicket::find($request->ticket);

        $history = json_decode($ticket->state_history);

        $history[] = [
            'etat' => $request->state,
            'date' => date('Y-m-d H:i:s'),
            'user' => Auth::user()->id
        ];

        $ticket->state = $request->state;
        $ticket->state_history = json_encode($history);

        $ticket->save();

        return response()->json('ok');
    }
}
