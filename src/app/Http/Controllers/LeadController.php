<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function leadsList(){
        $data = array();
        $leads = Lead::all();
        foreach ($leads as $lead) {
            $data[] = [
                'id' => $lead->id,
                'name' => $lead->name,
                'effectif' => $lead->getTotalEffectif(),
                'cycles' => explode(',',$lead->cycles),
                'city' => $lead->city,
                'presentation' => $lead->presentation,
                'adresse' => $lead->adresse,
                'localisation' => $lead->localisation,
                'link' => $lead->link,
                'last_contact' => Carbon::parse($lead->last_contact_date)->format('d/m/Y'),
                'logo' => $lead->getLogo(),
                'sales_manager' => ($lead->salesManager?[ 'name' => $lead->salesManager->getNomComplet(), 'img' => $lead->salesManager->getPicture() ]:null)
            ];
        }
        
        return response()->json($data);
    }

    public function saveLead(Request $request){
        
        if($request->lead){
            $lead = Lead::find($request->lead);
        }else{
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

        $sales_manager = User::find($request->salesManager);

        $lead->salesManager()->associate($sales_manager);

        $lead->save();

        if($request->hasFile('logo')){
            
            $filename = $lead->id.'-'.Helpers::getAlias($lead->name).'.'.$request->logo->extension();
            $request->logo->storeAs('leads',$filename,'public');
            $lead->update(['logo'=>$filename]);
        }
        
        if($request->hasFile('banner')){
            $filename = $lead->id.'-'.Helpers::getAlias($lead->name).'-banner.'.$request->banner->extension();
            $request->banner->storeAs('leads',$filename,'public');
            $lead->update(['banner'=>$filename]);
        }

        return response()->json('ok');
    }

    public function getLeadFormInfo(Request $request , $id){
        $lead = Lead::find($id);
        $pricing = json_decode($lead->pricing);
        $data = [
            'id' => $lead->id,
            'name' => $lead->name,
            'localisation' => $lead->localisation,
            'effectif' => $lead->effectif,
            'effectif_creche' => $lead->effectif_creche,
            'effectif_maternelle' => $lead->effectif_maternelle,
            'effectif_primaire' => $lead->effectif_primaire,
            'effectif_college' => $lead->effectif_college,
            'effectif_lycee' => $lead->effectif_lycee,
            'price_creche' => ($pricing?$pricing->creche->price:null),
            'inscription_creche' => ($pricing?$pricing->creche->inscription:null),
            'price_maternelle' => ($pricing?$pricing->maternelle->price:null),
            'inscription_maternelle' => ($pricing?$pricing->maternelle->inscription:null),
            'price_primaire' => ($pricing?$pricing->primaire->price:null),
            'inscription_primaire' => ($pricing?$pricing->primaire->inscription:null),
            'price_college' => ($pricing?$pricing->college->price:null),
            'inscription_college' => ($pricing?$pricing->college->inscription:null),
            'price_lycee' => ($pricing?$pricing->lycee->price:null),
            'inscription_lycee' => ($pricing?$pricing->lycee->inscription:null),
            'cycles' => explode(',',$lead->cycles),
            'links' => json_decode($lead->social_links),
            'city' => $lead->city,
            'dateStart' => $lead->start_year,
            'presentation' => $lead->presentation,
            'adresse' => $lead->adresse,
            'first_contact_date' => explode(' ',$lead->first_contact_date)[0],
            'last_contact_date' =>  explode(' ',$lead->last_contact_date)[0],
            'salesManager' =>  ($lead->salesManager?$lead->salesManager->id:null),
            'logo' => $lead->getLogo(),
            'banner' => $lead->getBanner(),
        ];
        return response()->json($data);
    }

    public function deleteLead($id){
        $lead = Lead::find($id);
        
        $lead->delete();

        $this->leadsList();
    }

    public function getLeadContacts($id){

        $lead = Lead::find($id);

        $contacts = [];

        foreach ($lead->contacts()->get() as $contact) {
            $contacts[] = [
                'id' =>$contact->id,
                'nom' => $contact->getNomComplet(),
                'fonction' => $contact->function,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'picture' => $contact->getPicture()
            ];
        }

        return $contacts;

    }


    public function saveLeadContact(Request $request,$id){

        if($request->contact){

            $contact = LeadContact::find($request->contact);
        }else{
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

        if($request->hasFile('picture')){
            $filename = $contact->id.'-'.$contact->name.'-'.$contact->last_name.'.'.$request->picture->extension();
            $request->picture->storeAs('leads/contacts',$filename,'public');
            $contact->update(['picture'=>$filename]);
        }

        return $this->getLeadContacts($id);
    }

    public function getContactFormInfo($id){
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

    public function getLeadsCoordinates(){
        $data = [];
        foreach (Lead::all() as $key => $lead) {
            if($lead->localisation)
            $data[] = [
                'position'=> explode(',',$lead->localisation),
                'icon' =>   $lead->getLogo(),
                'title' =>   $lead->name,
                'info' => '<h1>'.$lead->name.'</h1> <img width="100px" src="'.$lead->getLogo().'" />'
            ];
        }
        return response()->json($data);
    }
}
