<?php

namespace App\Http\Controllers\Paramettrage;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\School;
use App\Models\SchoolChecklist;
use App\Models\SchoolContact;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Stringable;

class SchoolController extends Controller
{
    public function schoolsList(){
        $data = array();
        $schools = School::where('hide_at',0)->get();
        foreach ($schools as $school) {
            $data[] = [
                'id' => $school->id,
                'name' => $school->name,
                'effectif' => $school->effectif,
                'cycles' => explode(',',$school->cycles),
                'types' => explode(',',$school->types),
                'city' => $school->city,
                'presentation' => $school->presentation,
                'adresse' => $school->adresse,
                'localisation' => $school->localisation,
                'link' => $school->link,
                'logo' => $school->getLogo(),
                'responsable' => !$school->accountManager?null:[
                    'id' => $school->accountManager->id,
                    'name' => $school->accountManager->getNomComplet(),
                    'img' => $school->accountManager->getPicture(),
                ]
            ];
        }
        return response()->json($data);
    }


    // ! get the deleted schools
    public function deletedSchools()
    {
        $data = [];
        $schools = School::onlyTrashed()->get();
        foreach($schools as $school){
            $data[] = [
                'id' => $school->id,
                'name' => $school->name,
                'effectif' => $school->effectif,
                'cycles' => explode(',',$school->cycles),
                'types' => explode(',',$school->types),
                'city' => $school->city,
                'presentation' => $school->presentation,
                'adresse' => $school->adresse,
                'localisation' => $school->localisation,
                'link' => $school->link,
                'logo' => $school->getLogo(),
                'responsable' => !$school->accountManager?null:[
                    'id' => $school->accountManager->id,
                    'name' => $school->accountManager->getNomComplet(),
                    'img' => $school->accountManager->getPicture(),
                ]
            ];
        } 
        return response()->json($data);
    }

    // ! get the hided schools
    public function disabledSchools()
    {
        $data = [];
        $schools = School::where('hide_at',1)->get();
        foreach ($schools as $school) {
            $data[] = [
                'id' => $school->id,
                'name' => $school->name,
                'effectif' => $school->effectif,
                'cycles' => explode(',',$school->cycles),
                'types' => explode(',',$school->types),
                'city' => $school->city,
                'presentation' => $school->presentation,
                'adresse' => $school->adresse,
                'localisation' => $school->localisation,
                'link' => $school->link,
                'logo' => $school->getLogo(),
                'responsable' => !$school->accountManager?null:[
                    'id' => $school->accountManager->id,
                    'name' => $school->accountManager->getNomComplet(),
                    'img' => $school->accountManager->getPicture(),
                ]
            ];
        }
        // $data = $this->schoolsList();
        return response()->json($data);
    }

    public function deleteSchool($id){
        $school = School::find($id);
        $school->delete();
    }

    public function softSchool($id)
    {
        $school = School::onlyTrashed()->where('id',$id)->first();
        $school->restore();
    }

    // ! save the status of school that is disabled or not
    public function disableSchool($id)
    {
        $school = School::find($id);
        $school->hide_at = 1;
        $school->save();
    }

    // ! save the enabled school
    public function enabledSchool($id)
    {
        $school = School::find($id);
        $school->hide_at = 0;
        $school->save();
    }

    public function saveSchool(Request $request){
        
        if($request->school){
            $school = School::find($request->school);
        }else{
            $school = new School();
        }

        // dd($request->all());
        // exit;

        $school->name = $request->name;
        $school->effectif_creche = $request->effectifCreche;
        $school->effectif_maternelle = $request->effectifMaternelle;
        $school->effectif_primaire = $request->effectifPrimaire;
        $school->effectif_college = $request->effectifCollege;
        $school->effectif_lycee = $request->effectifLycee;
        $school->effectif_sup = $request->effectifSup;
        $school->pricing = $request->pricing;
        $school->cycles = $request->cycles;
        $school->types = $request->types;
        $school->city = $request->city;
        $school->presentation = $request->presentation;
        $school->adresse = $request->adresse;
        $school->start_year = $request->dateStart;
        $school->first_year_boti = $request->dateStartBoti;
        $school->social_links = $request->links;
        $school->localisation = $request->localisation;
        $school->version_ios = $request->version_ios;
        $school->version_android = $request->version_android;
        $school->web_link = $request->web_link;
        
        $accmanager = User::find($request->accountManager);

        $school->accountManager()->associate($accmanager);

        $school->save();

        if($request->hasFile('logo')){
            $filename = $school->id.'-'.Helpers::getAlias($school->name).'.'.$request->logo->extension();
            $request->logo->storeAs('schools',$filename,'public');
            $school->update(['logo'=>$filename]);
        }
        
        if($request->hasFile('banner')){
            // $filename = $school->id.'-'.Helpers::getAlias($school->name).'-banner.'.$request->banner->extension();
            $filename = $school->id.'-'.Helpers::getAlias($school->name).'-'.uniqid().'-banner.'.$request->banner->extension();
            $request->banner->storeAs('schools',$filename,'public');
            $school->update(['banner'=>$filename]);
        }

        return $school;
        return response()->json('ok');
    }

    public function getSchoolFormInfo(Request $request , $id){
        $school = School::find($id);
        $pricing = json_decode($school->pricing);
        $data = [
            'id' => $school->id,
            'name' => $school->name,
            'localisation' => $school->localisation,
            'effectif' => $school->effectif,
            'effectif_creche' => $school->effectif_creche,
            'effectif_maternelle' => $school->effectif_maternelle,
            'effectif_primaire' => $school->effectif_primaire,
            'effectif_college' => $school->effectif_college,
            'effectif_lycee' => $school->effectif_lycee,
            'effectif_sup' => $school->effectif_sup,
            'price_creche' => ($pricing&&isset($pricing->creche)&&$pricing->creche?$pricing->creche->price:null),
            'inscription_creche' => ($pricing&&isset($pricing->creche)&&$pricing->creche?$pricing->creche->inscription:null),
            'price_maternelle' => ($pricing&&isset($pricing->maternelle)&&$pricing->maternelle?$pricing->maternelle->price:null),
            'inscription_maternelle' => ($pricing&&isset($pricing->maternelle)&&$pricing->maternelle?$pricing->maternelle->inscription:null),
            'price_primaire' => ($pricing&&isset($pricing->primaire)&&$pricing->primaire?$pricing->primaire->price:null),
            'inscription_primaire' => ($pricing&&isset($pricing->primaire)&&$pricing->primaire?$pricing->primaire->inscription:null),
            'price_college' => ($pricing&&isset($pricing->college)&&$pricing->college?$pricing->college->price:null),
            'inscription_college' => ($pricing&&isset($pricing->college)&&$pricing->college?$pricing->college->inscription:null),
            'price_lycee' => ($pricing&&isset($pricing->lycee)&&$pricing->lycee?$pricing->lycee->price:null),
            'inscription_lycee' => ($pricing&&isset($pricing->lycee)&&$pricing->lycee?$pricing->lycee->inscription:null),
            'price_sup' => ($pricing&&isset($pricing->sup)&&$pricing->sup?$pricing->sup->price:null),
            'inscription_sup' => ($pricing&&isset($pricing->sup)&&$pricing->sup?$pricing->sup->inscription:null),
            'cycles' => ($school->cycles?explode(',',$school->cycles):[]),
            'types' => ($school->types?explode(',',$school->types):[]),
            'links' => json_decode($school->social_links),
            'city' => $school->city,
            'dateStart' => $school->start_year,
            'dateStartBoti' => $school->first_year_boti,
            'presentation' => $school->presentation,
            'version_ios' => $school->version_ios,
            'version_android' => $school->version_android,
            'adresse' => $school->adresse,
            'web_link' => $school->web_link,
            'accountManager' => ( $school->accountManager?$school->accountManager->id:null),
            'logo' => $school->getLogo(),
            'banner' => $school->getBanner(),
        ];
        return response()->json($data);
    }


    public function getSchoolContacts($id){

        $school = School::find($id);

        $contacts = [];

        foreach ($school->contacts()->get() as $contact) {
            $contacts[] = [
                'id' =>$contact->id,
                'name' => $contact->name,
                'last_name' => $contact->last_name,
                'fonction' => $contact->function,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'picture' => $contact->getPicture()
            ];
        }

        return $contacts;

    }

    public function getSchoolIntervention($id){

        $school = School::find($id);

        $interventions = [];
        foreach ($school->interventions()->get() as $intervention) {
            $interventions[] = [
                'id' => $intervention->id,
                'school' => [
                    'id'=>$intervention->school->id,
                    'img'=>$intervention->school->getLogo(),
                    'name'=>$intervention->school->name
                ],
                'label' => $intervention->label,
                'details' => $intervention->details,
                'nature' => $intervention->nature,
                'date' => [
                    'day'=>Helpers::dateFormat($intervention->date,'%d'),
                    'month'=>Helpers::dateFormat($intervention->date,'%b')
                ]
            ];
        }

        return response()->json($interventions);
    }

    public function getSchoolTickets($id){

        $school = School::find($id);

        $interventions = [];
        foreach ($school->tickets()->get() as $intervention) {
            $interventions[] = [
                'id' => $intervention->id,
                'responsable' => [
                    'id'=>$intervention->responsable->id,
                    'img'=>$intervention->responsable->getPicture(),
                    'name'=>$intervention->responsable->name
                ],
                'label' => $intervention->label,
                'details' => $intervention->details,
                'nature' => $intervention->nature,
                'genre' => $intervention->genre
            ];
        }

        return response()->json($interventions);
    }

    public function saveSchoolContact(Request $request,$id){

        if($request->contact){

            $contact = SchoolContact::find($request->contact);
        }else{
            $contact = new SchoolContact();

        }

        $school = School::find($id);
        
        $contact->school()->associate($school);

        $contact->name = $request->name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->function = $request->function;
        $contact->enabled = 1;

        $contact->save();

        if($request->hasFile('picture')){
            $filename = $contact->id.'-'.$contact->name.'-'.$contact->last_name.'.'.$request->picture->extension();
            $request->picture->storeAs('schools/contacts',$filename,'public');
            $contact->update(['picture'=>$filename]);
        }

        return $this->getSchoolContacts($id);
    }


    public function updateSchoolContact(Request $request)
    {
        // $school  = School::find($request->schoolId);
        $contact = SchoolContact::find($request->id);

        // $contact->school()->associate($school);

        $contact->name      = $request->name;
        $contact->last_name = $request->last_name;
        $contact->email     = $request->email;
        $contact->phone     = $request->phone;
        $contact->function  = $request->function;
        $contact->save();
        if($request->hasFile('picture')){
            $filename = $contact->id.'-'.$contact->name.'-'.$contact->last_name.'.'.$request->picture->extension();
            $request->picture->storeAs('schools/contacts',$filename,'public');
            $contact->update(['picture'=>$filename]);
        }
        return response()->json('updated successfully');
    }

    public function getContactFormInfo($id){
        $contact = SchoolContact::find($id);

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

    public function getSchoolsCoordinates(){
        $data = [];
        foreach (School::all() as $key => $school) {
            if($school->localisation)
            $data[] = [
                'position'=> explode(',',$school->localisation),
                'icon' =>   $school->getLogo(),
                'title' =>   $school->name,
                'info' => '<h1>'.$school->name.'</h1> <img width="100px" src="'.$school->getLogo().'" />'
            ];
        }
        return response()->json($data);
    }

    public function getSchoolchecklists($id){

        $school = School::find($id);
        $checklists = [];
        
        foreach ($school->getChecklists() as $checklist) {
            $items = [];

            foreach ($checklist->items()->orderBy('ordre')->get() as $itm) {
                $schoolCheckItem = $school->school_checklist_item()->where('checklist_item_id',$itm->id)->first();
                $items[] = [
                    'id' => $itm->id,
                    'label' => $itm->label,
                    'state' => [
                        'idState' => ($schoolCheckItem?$schoolCheckItem->id:null),
                        'done' => ($schoolCheckItem&&$schoolCheckItem->done?true:false),
                        'comment' => ($schoolCheckItem?$schoolCheckItem->comment:null)
                    ]
                ];
            }

            $checklists[] = [
                'id' => $checklist->id,
                'label' => $checklist->label,
                'presentation' => $checklist->presentation,
                'items' => $items,
                'pourcentage' => $checklist->getPourcentage($school),
                'pourcentageClass' => ($checklist->getPourcentage($school)>75?'success':($checklist->getPourcentage($school)>30?'warning':'danger')),
            ];
        }

        return $checklists;
    }

    public function saveSchoolChecklist(Request $request,$id){
        $school = School::find($id);

        $checklist = Checklist::find($request->checklist);

        $checksIds = ($school->checklists?json_decode($school->checklists):[]);

        if(!in_array($request->checklist,$checksIds)){

            $checksIds[] = $request->checklist;
            
            $school->update(['checklists'=>json_encode($checksIds)]);
            
            foreach ($checklist->items()->get() as $item) {
                $checklistItem = new SchoolChecklist();
                
                $checklistItem->school()->associate($school);
                $checklistItem->checklist_item()->associate($item);
                
                $checklistItem->save();
            }
        }

        return $this->getSchoolchecklists($id);
    }

    public function updateStateCheckItem(Request $request){

        $user = Auth::user();

        $school_checklist = SchoolChecklist::find($request->item);
        if($request->state){
            $school_checklist->done = json_encode(['user'=>$user->id,'date'=>date('Y-m-d H:i:s')]);
        }else{
            $school_checklist->done = null;
        }

        $school_checklist->save();

        return $this->getSchoolchecklists($school_checklist->school->id);
    }

    public function deleteChecklistSchool(Request $request){
        $school = School::find($request->school);
        $checklist = Checklist::find($request->checklist);

        $itemsIds = [];
        
        foreach ($checklist->items()->get() as $item) {
            $itemsIds[] = $item->id;
        }

        // dd(implode(',',$itemsIds));

        // dd($school->school_checklist_item()->where('checklist_item_id','IN','('.implode(',',$itemsIds).')'));
        // exit;
        
        foreach ($school->school_checklist_item()->whereIn('checklist_item_id',$itemsIds)->get() as $checklistItem) {
            $checklistItem->delete();
        }

        $checklists = json_decode($school->checklists,true);

        if($checklists){

            $key = array_search($checklist->id, $checklists);
            
            if($key !== false){
                array_splice($checklists,$key,1);
                $school->update(['checklists'=>json_encode($checklists)]);
            }
        }

        return $this->getSchoolchecklists( $school->id );
    }

    public function getUsageTracking(Request $request){
        $schools = School::whereNotNull('web_link')->get();
        $tracking = [];

        foreach ($schools as $key => $school) {
            $response = Http::get('http://boti.education/'.$school->web_link.'/csmapi/onboarding');
            // print_r($response->json());exit;
            $data = $response->json();
            $data['school'] = [
                'id'=>$school->id,
                'logo'=>$school->getLogo(),
                'name'=>$school->name
            ];
            $tracking[] = $data;
        }

        return response()->json($tracking);
    }


    public function school_links()
    {
        $school_links = School::select('web_link')->get();
        return response()->json($school_links);
    }
}
