<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Depense;
use App\Models\Lead;
use App\Models\Lead_interv;
use App\Models\LeadContact;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function leadsList()
    {
        $data = array();
        $leads = Lead::where('is_converted', 0)->orderBy('created_at', 'desc')->get();
        foreach ($leads as $lead) {
            $data[] = [
                'id' => $lead->id,
                'state' => $lead->status,
                'name' => $lead->name,
                'effectif' => $lead->getTotalEffectif(),
                'cycles' => explode(',', $lead->cycles),
                'city' => $lead->city,
                'presentation' => $lead->presentation,
                'adresse' => $lead->adresse,
                'localisation' => $lead->localisation,
                'link' => $lead->link,
                'last_contact' => Carbon::parse($lead->last_contact_date)->format('d/m/Y'),
                'created_at' => Carbon::parse($lead->created_at)->format('d/m/Y'),
                'logo' => $lead->getLogo(),
                'source' => $lead->source,
                'comment' => $lead->comment,
                'is_converted' => $lead->is_converted,
                'status' => $lead->status,
                'sales_manager' => ($lead->salesManager ? ['name' => $lead->salesManager->getNomComplet(), 'img' => $lead->salesManager->getPicture()] : null)
            ];
        }

        return response()->json($data);
    }

    public function leads()
    {
        $leads = Lead::select('id', 'name')->where('is_converted', 0)->get();
        return response()->json($leads);
    }

    function changeProsp(Request $req)
    {

        $lead = Lead::find($req->id);
        $lead->status = $req->state;
        $lead->last_contact_date = date('Y-m-d H:i:s');
        $lead->save();

        return response()->json(['data' => $req->state]);
    }
    function contrat_type(Request $req)
    {

        $lead = Lead::find($req->id);
        $lead->contrat = $req->state;
        $lead->save();

        return response()->json(['data' => $req->state]);
    }

    public function saveLead(Request $request)
    {


        if ($request->lead) {
            $lead = Lead::find($request->lead);
        } else {
            $lead = new Lead();
        }

        // dd($request->all());
        // exit;

        $lead->name = $request->name;
        $lead->effectif_creche = $request->effectifCreche;
        $lead->effectif_maternelle = $request->effectifMaternelle;
        $lead->effectif_primaire = $request->effectifPrimaire;
        $lead->effectif_college = $request->effectifCollege;
        $lead->effectif_lycee = $request->effectifLycee;
        $lead->pricing = $request->pricing;
        $lead->cycles = $request->cycles;
        $lead->city = $request->city;
        $lead->presentation = $request->presentation;
        $lead->adresse = $request->adresse;
        $lead->start_year = $request->dateStart;
        $lead->social_links = $request->links;
        $lead->localisation = $request->localisation;
        $lead->first_contact_date = $request->first_contact_date;
        $lead->last_contact_date = $request->last_contact_date;
        $lead->source = $request->source;
        $lead->comment = $request->comment;
        $lead->created_by = Auth::user()->id;
        $sales_manager = User::find($request->salesManager);

        $lead->salesManager()->associate($sales_manager);

        $lead->save();

        if ($request->hasFile('logo')) {

            $filename = $lead->id . '-' . Helpers::getAlias($lead->name) . '.' . $request->logo->extension();
            $request->logo->storeAs('leads', $filename, 'public');
            $lead->update(['logo' => $filename]);
        }

        if ($request->hasFile('banner')) {
            $filename = $lead->id . '-' . Helpers::getAlias($lead->name) . '-banner.' . $request->banner->extension();
            $request->banner->storeAs('leads', $filename, 'public');
            $lead->update(['banner' => $filename]);
        }

        return response()->json('ok');
    }

    public function getLeadFormInfo(Request $request, $id)
    {

        $user = User::find(Auth::user()->id);
        $is_admin = $user->role->Alias == 'admin' || $user->role->Alias == 'sales_manager' ? true : false;

        $lead = Lead::find($id);
        if ($lead->is_converted != 0) {
            return response()->json(['res' => $lead->is_converted]);
        } else {
            $user = $lead->created_by ? User::find($lead->created_by)->getNomComplet() : '';
            $pricing = json_decode($lead->pricing);
            $data = [
                'id' => $lead->id,
                'state' => $lead->status,
                'name' => $lead->name,
                'localisation' => $lead->localisation,
                'links' => json_decode($lead->social_links),
                'city' => $lead->city,
                'dateStart' => $lead->start_year,
                'presentation' => $lead->presentation,
                'adresse' => $lead->adresse,
                'first_contact_date' => explode(' ', $lead->first_contact_date)[0],
                'last_contact_date' =>  explode(' ', $lead->last_contact_date)[0],
                'salesManager' => ($lead->salesManager ? $lead->salesManager->getNomComplet() : null),
                'logo' => $lead->getLogo(),
                'banner' => $lead->getBanner(),
                'types' => Lead::states(),
                'source' => $lead->source,
                'comment' => $lead->comment,
                'is_converted' => $lead->is_converted,
                'created_by' =>  $user,
                'created_at' => date('d m Y', strtotime($lead->created_at)),
                'is_admin' => $is_admin,
                'contrat' => $lead->contrat,
            ];

            return response()->json($data);
        }
    }

    public function deleteLead($id)
    {
        $lead = Lead::find($id);

        $lead->delete();

        $this->leadsList();
    }

    public function getLeadContacts($id)
    {

        $lead = Lead::find($id);

        $contacts = [];

        foreach ($lead->contacts()->get() as $contact) {
            $contacts[] = [
                'id' => $contact->id,
                'nom' => $contact->getNomComplet(),
                'fonction' => $contact->function,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'picture' => $contact->getPicture()
            ];
        }

        return $contacts;
    }


    public function saveLeadContact(Request $request, $id)
    {

        if ($request->contact) {

            $contact = LeadContact::find($request->contact);
        } else {
            $contact = new LeadContact();
        }

        $lead = Lead::find($id);

        $contact->lead()->associate($lead);

        $contact->name = $request->name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->function = $request->function;
        $contact->enabled = 1;

        $contact->save();

        if ($request->hasFile('picture')) {
            $filename = $contact->id . '-' . $contact->name . '-' . $contact->last_name . '.' . $request->picture->extension();
            $request->picture->storeAs('leads/contacts', $filename, 'public');
            $contact->update(['picture' => $filename]);
        }

        return $this->getLeadContacts($id);
    }

    public function getContactFormInfo($id)
    {
        $contact = LeadContact::find($id);

        $data = [
            'id' => $contact->id,
            'name' => $contact->name,
            'last_name' => $contact->last_name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'function' => $contact->function,
        ];

        return response()->json($data);
    }

    public function getLeadsCoordinates()
    {
        $data = [];
        foreach (Lead::all() as $key => $lead) {
            if ($lead->localisation)
                $data[] = [
                    'position' => explode(',', $lead->localisation),
                    'icon' =>   $lead->getLogo(),
                    'title' =>   $lead->name,
                    'info' => '<h1>' . $lead->name . '</h1> <img width="100px" src="' . $lead->getLogo() . '" />'
                ];
        }
        return response()->json($data);
    }


    public function save_intervention(Request $request, $id = null)
    {
        if ($id) {
            $intervention = Lead_interv::find($id);
        } else {
            $intervention = new Lead_interv();
        }

        $intervention->leads_id  = $request->lead_id;
        $intervention->sales_id  = $request->sales_id;
        $intervention->label     = $request->label;
        $intervention->details   = $request->details;
        $intervention->date      = $request->date;
        $intervention->type      = $request->type;
        $intervention->nature    = $request->nature;
        $intervention->channel   = $request->channel;
        $intervention->status    = $request->status;
        $intervention->state    = $request->duration;
        
        $intervention->save();
     

        $lead = Lead::find($request->lead_id);
        $lead->last_contact_date = date('y-m-d h:m:i');
        $lead->save();

        $sales = User::where('id', $request->sales_id)->first();
        $data = [
            'id' => $intervention->id,
            'details' => $request->details,
            'label' => $request->label,
            'date' => [
                'day' => Helpers::dateFormat($request->date, '%d'),
                'month' => Helpers::dateFormat($request->date, '%b')
            ],
            'type' => $request->type,
            'nature' => $request->nature,
            'channel' => $request->channel,
            'sales' => $sales->nom . ' ' . $sales->prenom,
            'status' => $request->status,
            'comment' => $request->comment
        ];


        if ($request->costs) {
            $depense = new Depense();
            $depense->intervention_lead_id = $intervention->id;
            $depense->date = date('Y-m-d H:i:s', strtotime($request->date));
            $depense->user = Auth::user()->nom . ' ' . Auth::user()->prenom;
            $depense->rubriques = implode(',', array_filter($request->rubrique));
            $depense->amounts =  implode(',', array_filter($request->amount));
            $depense->save();
        }

        return response()->json($data);
    }

    public function getInterventions($id)
    {
        $interventions = Lead_interv::where("leads_id", $id)->orderBy('date', 'desc')->get();
        $data = array();
        foreach ($interventions as  $inter) {
            $sales = User::where('id', $inter->sales_id)->first();
            $data[] = [
                'id' => $inter->id,
                'details' => $inter->details,
                'label' => $inter->label,
                'date' => [
                    'day' => Helpers::dateFormat($inter->date, '%d'),
                    'month' => Helpers::dateFormat($inter->date, '%b')
                ],
                'type' => $inter->type,
                'nature' => $inter->nature,
                'channel' => $inter->channel,
                'sales' => $sales->nom . ' ' . $sales->prenom,
                'sales_id' => $sales->id,
                'date_item' => $inter->date,
                'status' => $inter->status,
                'duration' => $inter->state,
            ];
        }
        return response()->json($data);
    }


    public function getIntervention($id)
    {
        $intervention = Lead_interv::find($id)->get();
        return response()->json($intervention);
    }


    public function cities()
    {
        $cities = Lead::select('city')->distinct()->get(['city']);
        return response()->json($cities);
    }

    public function kanban($city = null)
    {
        $states = Lead::states();
        $leads_by_states = array();
        foreach ($states as $key => $value) {
            if ($city) {
                $leads_by_states[$value] = Lead::where([['status', $key], ['city', $city]])->get();
            } else {
                $leads_by_states[$value] = Lead::where('status', $key)->get();
            }
        }

        $data = [];

        foreach ($leads_by_states as $key => $values) {
            if (count($values) == 0) {
                $data[$key] = [];
            } else {
                foreach ($values as $value) {
                    $sale = $value->sales_manager ?  User::find($value->sales_manager) : '';
                    $data[$key][] = [
                        'sale' => $value->sales_manager ? $sale->getNomComplet() : '',
                        'sale_picture' => $value->sales_manager ? $sale->getNomComplet() : '',
                        'city' => $value->city,
                        'name' => $value->name,
                        'logo' => $value->getLogo(),
                        'last_contact' => $value->last_contact_date ? date('d-m-Y', strtotime($value->last_contact_date)) : null,
                        'id' => $value->id,
                        'is_converted' => $value->is_converted
                    ];
                }
            }
        }

        return response()->json($data);
    }

    public function kanban_user($id)
    {
        $states = Lead::states();
        $leads_by_states = array();

        foreach ($states as $key => $value) {
            $leads_by_states[$value] = Lead::where([['status', $key], ['sales_manager', $id]])->get();
        }

        $data = [];

        foreach ($leads_by_states as $key => $values) {
            if (count($values) == 0) {
                $data[$key] = [];
            } else {
                foreach ($values as $value) {
                    $sale =  User::find($value->sales_manager);
                    $data[$key][] = [
                        'sale' => $sale->getNomComplet(),
                        'sale_picture' => $sale->getPicture(),
                        'city' => $value->city,
                        'name' => $value->name,
                        'logo' => $value->getLogo(),
                        'last_contact' => $value->last_contact_date ? date('d-m-Y', strtotime($value->last_contact_date)) : null,
                    ];
                }
            }
        }

        return response()->json($data);
    }

    public function lead_to_school(Request $request)
    {

        $lead_id = $request->lead_id;
        $lead  = Lead::find($lead_id);


        $school = new School();
        $school->name = $lead->name;
        $school->logo = $lead->logo;
        $school->banner = $lead->banner;
        $school->effectif = $lead->effectif;
        $school->cycles = $lead->cycles;
        $school->city = $lead->city;
        $school->presentation = $lead->presentation;
        $school->adresse = $lead->adresse;
        $school->localisation = $lead->localisation;
        $school->social_links = $lead->social_links;
        $school->effectif_maternelle = $lead->effectif_maternelle;
        $school->effectif_primaire = $lead->effectif_primaire;
        $school->effectif_lycee = $lead->effectif_lycee;
        $school->effectif_creche = $lead->effectif_creche;
        $school->effectif_college = $lead->effectif_college;
        $school->pricing = $lead->pricing;
        $school->start_year = date('Y');
        $school->first_year_boti = date('Y');
        $school->by_lead = $lead->id;
        $school->save();

        $lead->is_converted = $school->id;
        $lead->save();

        return response()->json($school->id);
    }


    public function deletesLead($id)
    {
        $lead = Lead::find($id);
        $lead->delete();
    }
}
