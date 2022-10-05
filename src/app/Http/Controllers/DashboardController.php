<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Contact;
use App\Models\Quotation;
use App\Models\School;
use App\Models\SchoolIntervention;
use App\Models\SchoolTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function getEtatTraitementIncidents(){
        $ticketIncidentTraites = SchoolTicket::query()->where('state','LIKE','traite')->get();
        $ticketIncidentNonTraites = SchoolTicket::query()->where('state','NOT LIKE','traite')->get();

        $traite =  (count($ticketIncidentTraites)!=0||count($ticketIncidentNonTraites)!=0?count($ticketIncidentTraites):100);
        $non_traite =  count($ticketIncidentNonTraites);
        $data['traite'] = ($traite*100/($traite+$non_traite)); 
        $data['non_traite'] = ($non_traite*100/($traite+$non_traite)); 
        return response()->json($data);
    }

    public function getlastIntervention(){
        $intervention  = SchoolIntervention::query()->where('state' , '<>' , 'traite')->latest('updated_at')->first();
        if($intervention){

            $data = [ 
                'label' => $intervention->label,
                'date' => [
                    'day'=>Helpers::dateFormat($intervention->date,'%d'),
                    'month'=>Helpers::dateFormat($intervention->date,'%b')
                ],
                'school' => $intervention->school->getLogo(),
                'responsable' => ['img'=>$intervention->responsable->getPicture(),'name'=>$intervention->responsable->getNomComplet()]
            ];
        }else{
            $data = [];
        }

        return response()->json($data);
    }
    
    public function getLastTicket(){
        $ticket  = SchoolTicket::query()->where('state' , '<>' , 'traite')->latest('updated_at')->first();
        if($ticket){

            $data = [ 
                'label' => $ticket->label,
                'school' => ($ticket->school?$ticket->school->getLogo():null),
                'responsable' => ['img'=>$ticket->responsable->getPicture(),'name'=>$ticket->responsable->getNomComplet()]
            ];
        }else{
            $data = [];
        }
        return response()->json($data);
    }
    
    public function getDasboardTickets(Request $request){
        $where = [];

        if($request->nature){
            $where[] = ['nature', 'LIKE' , $request->nature];
        }

        if($request->genre){
            $where[] = ['genre', 'LIKE' , $request->genre];

        }

        if($request->etat){
            $where[] = ['state', 'LIKE' , $request->etat];

        }

        if($request->priorite){
            $where[] = ['priority', 'LIKE' , $request->priorite];

        }
        if($where){
            $tickets = SchoolTicket::where($where)->orderBy('date','desc')->get();
        }else{
            $tickets = SchoolTicket::orderBy('date','desc')->get();
        }

        $data = [];

        foreach ($tickets as $ticket) { 
            $school = null;
            if($ticket->school){
                $school = [
                    'id' => $ticket->school->id,
                    'name' => $ticket->school->name,
                    'img' => $ticket->school->getLogo()
                ];
            }
            $data[] = [
                'id' => $ticket->id,
                'label' => $ticket->label,
                'details' => $ticket->details,
                'school' => $school,
                'responsable' => $ticket->responsable->getPicture(),
                'nature' => ['label'=>$ticket->nature,'color'=>'success'],
                'genre' => ['label'=>$ticket->genre,'color'=>'success'],
                'date' => $ticket->date,
                'priorite' => ['label'=>$ticket->priority,'color'=>'success'],
                'etat' => ['label'=>$ticket->state,'color'=>'success']
            ];
        }

        return response()->json($data);
    }
    
    public function getDasboardInterventions(Request $request){
        
        $interventions = SchoolIntervention::query()->where('state', 'NOT LIKE' , 'traite')->orderBy('date','desc')->get();
        
        $data = [];

        foreach ($interventions as $intervention) { 
            $school = null;
            if($intervention->school){
                $school = [
                    'id' => $intervention->school->id,
                    'name' => $intervention->school->name,
                    'img' => $intervention->school->getLogo()
                ];
            }
            $data[] = [
                'id' => $intervention->id,
                'label' => $intervention->label,
                'school' => $school,
                'responsable' => $intervention->responsable->getPicture(),
                'nature' => ['label'=>$intervention->nature,'color'=>'success'],
                'date' => [
                    'day'=>Helpers::dateFormat($intervention->date,'%d'),
                    'month'=>Helpers::dateFormat($intervention->date,'%b'),
                    'brut' => $intervention->date
                ]
            ];
        }

        return response()->json($data);
    }

    public function getDasboardPlanning(Request $request){
        $data = json_decode($request->info);
        $dateStart = explode('T',$data->start)[0];
        $dateEnd = explode('T',$data->end)[0];

        $interventions = SchoolIntervention::whereBetween("date",[$dateStart, $dateEnd])->get();

        $events = [];

        foreach ($interventions as $intervention) {
            $events[] = [
                  'title'=> $intervention->label,
                  'date'=> Helpers::dateFormat($intervention->date,'%Y-%m-%d'),
                  'start'=> Helpers::dateFormat($intervention->date,'%Y-%m-%d'),
                  'end'=> Helpers::dateFormat($intervention->date,'%Y-%m-%d'),
                  'type'=> [ 'label' => $intervention->nature, 'color' => "#000" ],
                  'user'=> [
                    'img'=> $intervention->responsable->getPicture(),
                  ],
                
                ];
        }

        return json_encode($events);

    }

    public function getUserTicketBuffer(){

        $data = array();

        // $collaborateurs = User::getAccountManagers();
        $collaborateurs = User::all();

        foreach ($collaborateurs as $key => $collaborateur) {
            $tickets = $collaborateur->tickets()->get();
            $ticketClosed = 0; 
            foreach ($tickets as $key => $ticket) {
                if($ticket->state == 'closed')
                $ticketClosed++;
            }

            $data[] = [
                'id' => $collaborateur->id,
                'nom' => $collaborateur->getNomComplet(),
                'img' => $collaborateur->getPicture(),
                'tickets' => count($tickets),
                'tickets_closed' => $ticketClosed,
                'closing_percentage' => round((count($tickets)?($ticketClosed*100)/count($tickets):0),2)

            ];
        }

        return response()->json($data);

    }


    public function getDashboardStats(){
        $data = array();

        $schools = School::all();
        $schools_now = School::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $schools_past = School::where('created_at', '<' , Carbon::now()->subWeek()->endOfWeek())->get();
        
        $tickets = SchoolTicket::all();
        $tickets_now = SchoolTicket::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $tickets_past = SchoolTicket::where('created_at', '<' , Carbon::now()->subWeek()->endOfWeek())->get();

        $intervention = SchoolIntervention::all();
        $intervention_now = SchoolIntervention::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $intervention_past = SchoolIntervention::where('created_at', '<' , Carbon::now()->subWeek()->endOfWeek())->get();

        $data = [
            'schools' => [
                'count' => count($schools),
                'upratio' => round((count($schools)>0?(( count($schools_past) - count($schools_now))*100)/count($schools):0),2)
            ],
            'tickets' => [
                'count' => count($tickets),
                'upratio' => round((count($tickets)>0?(( count($tickets_past) - count($tickets_now))*100)/count($tickets):0),2)
            ],
            'intervention' => [
                'count' => count($intervention),
                'upratio' => round((count($intervention)>0?(( count($intervention_past) - count($intervention_now) )*100)/count($intervention):0),2)
            ],
        ];

        return response()->json($data);
    } 

    public function getContacts(){

        $data = array();

        $contacts = Contact::orderBy('created_at','desc')->get();

        foreach ($contacts as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'nom' => $value->nom,
                'tel' => $value->tel,
                'email' => $value->email,
                'sujet' => $value->sujet,
                'message' => $value->message,
                'date' => \App\Helpers::dateFormat($value->created_at),
            ];
        }
        return response()->json($data);

    }

    public function getDemandes(){

        $data = array();

        $contacts = Quotation::orderBy('created_at','desc')->get();

        foreach ($contacts as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'type' => $value->type,
                'niveaux' => $value->niveaux,
                'nombre' => $value->nombre,
                'pack' => $value->pack,
                'ville' => $value->ville,
                'nom' => $value->nom,
                'tel' => $value->tel,
                'email' => $value->email,
                'handling' => [
                    'handled' => ($value->handling?true:false),
                    'comment' => $value->handling,
                    'saved' => ($value->handling?true:false)
                ],
                'ecole' => $value->ecole,
                'date' => \App\Helpers::dateFormat($value->created_at),
                'files' => $value->getArrayFile()
            ];
        }
        return response()->json($data);

    }

    public function handleQuotation(Request $request){
        $quotation = Quotation::find($request->demande);

        $quotation->handling = $request->comment;
        $quotation->save();

        return response()->json('ok');
    }
}
