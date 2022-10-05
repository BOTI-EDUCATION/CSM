<?php

namespace App\Http\Controllers\Paramettrage;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(){
        
    }

    public function usersList(){
        $data = array();
        $users = User::all();
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'img' => $user->getPicture(),
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'fonction' => $user->fonction,
                'enabled' => ($user->enabled?true:false),
                'role' => $user->role->Label
            ];
        }
        return response()->json($data);
    }
    
    public function rolesList(){
        $data = array();
        $roles = Role::all();
        foreach ($roles as $role) {
            $data[] = [
                'id' => $role->id,
                'label' => $role->Label,
            ];
        }
        return response()->json($data);
    }
   
    public function getUserFormInfo(Request $request,$id){
        
        $user = User::find($id);
        $data = [
            'id' => $user->id,
            'role' => $user->role->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'fonction' => $user->fonction,
            'adresse' => $user->adresse,
            'image' => $user->getPicture(),
            'enabled' => ($user->enabled?true:false)
        ];
        return response()->json($data);
    }

    public function saveUser(Request $request){

        if($request->user){
            $user = User::find($request->user);
        }else{
            $user = new User();
        }
        
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->fonction = $request->fonction;
        $user->adresse = ($request->adresse?$request->adresse:'');
        $user->enabled = ($request->enabled&&$request->enabled!='false'?1:0);
        $user->password = Hash::make($request->email);
        
        $role = Role::find($request->role);

        $user->role()->associate($role);

        $user->save();

        if($request->hasFile('image')){
            $filename = $user->id.'-'.$user->nom.'-'.$user->prenom.'.'.$request->image->extension();
            $request->image->storeAs('users',$filename,'public');
            $user->update(['picture'=>$filename]);
        }

        return response()->json('ok');

    }

    public function getAccountManagers(){
        $users = User::query()->where('role_id','=',3)->get();
        $data = [];
        foreach ($users as $key => $user) {
            $data[] = [
                'id' => $user->id,
                'role' => $user->role->Label,
                'nom' => $user->nom.' '.$user->prenom,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'fonction' => $user->fonction,
                'adresse' => $user->adresse,
                'image' => $user->getPicture(),
                'enabled' => ($user->enabled?true:false)
            ];
        }
        return response()->json($data);
    }

    public function getUserTickets(){
        $user = Auth::user();
        $tickets = [];
        
        foreach ($user->tickets()->get() as $ticket) {
            $school = null;
            if($ticket->school){
                $school = [
                    'id' => $ticket->school->id,
                    'name' => $ticket->school->name,
                    'img' => $ticket->school->getLogo()
                ];
            }
            
            $tickets[] = [
                'id' => $ticket->id,
                'school' => $school,
                'label' => $ticket->label,
                'details' => $ticket->details,
                'date' => $ticket->date
            ];
        }

        return $tickets;      
    }

    public function getUserInterventions(){
        $user = Auth::user();
        $interventions = [];

        foreach ($user->interventions()->get() as $intervention) {
            $school = null;
            if($intervention->school){
                $school = [
                    'id' => $intervention->school->id,
                    'name' => $intervention->school->name,
                    'img' => $intervention->school->getLogo()
                ];
            }

            $interventions[] = [
                'id' => $intervention->id,
                'school' => $school,
                'label' => $intervention->label,
                'details' => $intervention->details,
                'date' => $intervention->date
            ];
        }

        return $interventions;      
    }

    public function changeUserPassword(Request $request , $id){

        $user = User::find($id);
        
        if($user){

            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return response()->json();
    }


    public function getSalesManagers(){
        $users = User::query()->where('role_id','=',4)->get();
        $data = [];
        foreach ($users as $key => $user) {
            $data[] = [
                'id' => $user->id,
                'role' => $user->role->Label,
                'nom' => $user->nom.' '.$user->prenom,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'fonction' => $user->fonction,
                'adresse' => $user->adresse,
                'image' => $user->getPicture(),
                'enabled' => ($user->enabled?true:false)
            ];
        }
        return response()->json($data);
    }

}
