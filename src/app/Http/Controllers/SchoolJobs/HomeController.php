<?php

namespace App\Http\Controllers\SchoolJobs;

use App\Http\Controllers\Controller;
use App\Models\JobOfferInfo;
use App\Models\JobCandidate;
use App\Models\JobOffer;
use App\Models\JobOfferCandidacies;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    function displayCandidat(Request $request)
    {
        //dd($request->tst2);
        //exit;
        // response()->json()
        return 'Candidat Added to DB';
    }

    function validateOfferCandidacy(Request $request){
        $validation = $request->validation;
        $idOffer=$request->idOffer;
        $idCandidat=$request->idCandidat;
        //return $request;
        DB::table('job_offer_candidacies')
        ->where('job_offer',intval($idOffer))
        ->orWhere('job_candidate',intval($idCandidat))
        ->update(['Validation' => intval($validation)]);

        return response()->json([
            'status' => 200,
            'msg' => 'Updated Successfully.'
        ], 200);
    }

    public function logoutCandidat()
    {
        $this->guard('apiCandidat')->logout();
        return response()->json([
            'status' => 200,
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    function deleteOffer($id){
        $offer = JobOffer::find($id);
        
        $offer->delete();

        $this->getAllOffers();

    }

    function postulerOffer($id){
        
    }

    function getConnectedCandidat(){
        $candidat = Auth::guard('apiCandidat')->user();
        // var_dump($candidat);
        return response()->json($candidat);
    }

    function loginCandidat(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = auth()->guard('apiCandidat')->attempt($credentials);
        if ($token) {
            return response()->json(['status' => 200, 'token' => $token], 200)->header('Authorization', $token);
        }
        //return response()->json(['error' => 'Email ou mot de passe incorrect !'], 401);
        return response()->json(['status' => 401], 401);
    }


    function getCandidat(Request $request, $email)
    {
        $user = JobCandidate::where('email', $email)->first();
        

        return $user;
    }

    public function getInfosList()
    {
     $infosList=JobOfferInfo::all();
    //  return $infosList;
     $data = array();

     foreach ($infosList as $info) {
        $data[] = [
            'id' => $info->id,
            'alias' => $info->alias,
            'label' => $info->label,
            'values_possible' => json_decode($info->values_possible),
        ];
     }
     return response()->json($data);
    }

    public function getAllOffers()
    {
        $offers = JobOffer::all();
        $data = array();
        foreach ($offers as $offer) {
            $school=School::where('id',$offer->ecole)->first();
            $schoolLogo=$school?$school->getLogo():'';
            $schoolName=$school?$school->name:'';
            $city=$offer->city!=NULL?$offer->city:'';

            $nbrOfferCandidacies=DB::table('job_offer_candidacies')
            ->join('job_offer','job_offer.id', '=', 'job_offer_candidacies.job_offer')
            ->select('job_offer_candidacies.*')
            ->where('job_offer_candidacies.job_offer',$offer->id)
            ->get()->count();
            $data[] = [
                'id' => $offer->id,
                'reference' => $offer->reference,
                'details' => $offer->details,
                'infos' => json_decode($offer->infos),
                'address' => $offer->address,
                'title' => $offer->title,
                'localization' => $offer->localization,
                'city' => $city,
                'introduction' => $offer->introduction,
                'date' => $offer->date,
                'schoolLogo'=>$schoolLogo,
                'nbrCandidates'=>$nbrOfferCandidacies,
                'ecole_name' => $schoolName,
                'picture' => $offer->getPicture(),
            ];
        } 
        

        return response()->json($data);
    }
    public function getOffer(Request $request , $id)
    {
        $offer = JobOffer::find($id);
        $school=School::where('id',$offer->ecole)->first();
            $schoolLogo=$school?$school->getLogo():'';
            $schoolName=$school?$school->name:'';
            $city=$offer->city!=NULL?$offer->city:'';

            $nbrOfferCandidacies=DB::table('job_offer_candidacies')
            ->join('job_offer','job_offer.id', '=', 'job_offer_candidacies.job_offer')
            ->select('job_offer_candidacies.*')
            ->where('job_offer_candidacies.job_offer',$offer->id)
            ->get()->count();
            $data= [
                'id' => $offer->id,
                'reference' => $offer->reference,
                'details' => $offer->details,
                'infos' => json_decode($offer->infos),
                'address' => $offer->address,
                'title' => $offer->title,
                'localization' => $offer->localization,
                'city' => $city,
                'introduction' => $offer->introduction,
                'date' => $offer->date,
                'schoolLogo'=>$schoolLogo,
                'nbrCandidates'=>$nbrOfferCandidacies,
                'ecole_name' => $schoolName,
                'picture' => $offer->getPicture(),
            ];

        return response()->json($data);
    }

    function getAllCandidats(Request $request)
    {
        $users = JobCandidate::all();
        $data = array();
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'cin' => $user->cin,
                'tel' => $user->telephone,
                'nationality'=>$user->nationality,
            ];
        }

        return response()->json($data);
    }
    
    function getAllCandidatsOffer(Request $request)
    {

        $offerCandidacies=JobOfferCandidacies::join('job_candidates','job_candidates.id', '=', 'job_candidate')
        ->where('job_offer',intval($request->id))
        ->select('job_candidates.*','validation')
        ->get();
        $data = array();
        foreach ($offerCandidacies as $offer) {
            $data[] = [
                'id' => $offer->id,
                'nom' => $offer->nom,
                'prenom' => $offer->prenom,
                'email' => $offer->email,
                'cin' => $offer->cin,
                'tel' => $offer->telephone,
                'validation'=>$offer->validation,
            ];
        }

        return response()->json($data);
    }
    


    function getAllOffersCandidacies(Request $request)
    {
        $users = JobOfferCandidacies::all();
        $data = array();
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'job_offer' => $user->job_offer,
                'job_candidate' => $user->job_candidate,
                'validation' => $user->validation,
            ];
        }

        return response()->json($data);
    }

    function getAllOffersCandidacies2(Request $request)
    {
        $users = JobOfferCandidacies::all();
        $data = array();
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'job_offer' => $user->job_offer,
                'job_candidate' => $user->job_candidate,
                'validation' => $user->validation,
            ];
        }

        return response()->json($data);
    }
    

    function insertNewOffer(Request $request)
    {
        //return  json_encode($request->infos);
        $last = DB::table('job_offer')->select("job_offer.id")->orderBy('id','desc')->first();
        $tableCount = JobOffer::count();
        $offer = new JobOffer();
        if($tableCount!=0){
            $offer->id=($last->id)+1;
        }else{
            $offer->id=1;
        }
        if($request->hasFile('image')){
            $file=$request->file("image"); 
            $filename = (($last->id)+1).'-'.$request->reference.'.'.$file->extension();
            $file->storeAs('jobOffersImages',$filename,'public');
            $offer->image=$filename;    
        }
        
        $offer->reference = (($last->id)+1)."/".date("Y");
        $offer->details = $request->details;
        $offer->address = $request->address;
        $offer->title = $request->title;
        $offer->localization = $request->localization;
        $offer->city = $request->city;
        $offer->ecole = $request->ecole;
        $offer->introduction = $request->introduction;
        $offer->date = $request->date;
        
        /*for($i=0;$i<count($request->infos);$i++){
            array_push($listInfos,$request->infos[$i]) ;
        }
        $offer->infos = json_encode($listInfos); */
        $listInfos=[];
        
        foreach ($request->infos as $infos) {
            $inf=json_decode($infos);
            $listInfos[] = [
                  'key' => $inf->key,
                  'value'=> $inf->value,   
                ];
        }
        $offer->infos = json_encode($listInfos);

        $offer->save();


        return response()->json([
            'status' => 200,
            'message' => 'Offer Added to DB',
        ]);
    }
    function addCandidat(Request $request)
    {
        //dd($request->tst2);
        //exit;
        // response()->json()
        $user = JobCandidate::where('email', '=', $request->email)->first();
        if ($user === null) {
            // user doesn't exist
            $candidat = new JobCandidate();
            $candidat->nom = $request->nom;
            $candidat->prenom = $request->prenom;
            $candidat->email = $request->email;
            $candidat->cin = $request->cin;
            $candidat->nationality = $request->nationality;
            $candidat->telephone = $request->telephone;
            $candidat->password = Hash::make($request->password);
            //$candidat->confirmedPassword = $request->confirmedPassword;

            //$candidat->adresse=$request->adresse;
            $candidat->save();


            return response()->json([
                'status' => 200,
                'message' => 'Candidat Added to database',
            ]);
        }else{
            return response()->json([
                'status' => 201,
                'message' => 'Candidat exist in DB',
            ]);
        }
    }

    function addJobOfferCandidacies(Request $request){

        // print_r($request->all());
        // exit;
         $offerCandidacies = new JobOfferCandidacies();
         $offerCandidacies->job_offer = $request->job_offer;
         $offerCandidacies->job_candidate = $request->job_candidate;
        

         if($request->hasFile('Files')){
            //return response()->json($request->Files);
            //return response()->json(['image' => 'exist']);

            $data = array();
            foreach($request->file("Files") as $file)
            {
    
            $filename = $request->job_offer.'-'.$request->job_candidate.'.'.$file->extension();
            $file->storeAs('candidats',$filename,'public');
            $data[] = $filename;
            }

         $offerCandidacies->files=json_encode($data);    
        }

         $offerCandidacies->save();


        return response()->json($request->all());

    }

    function addJobOfferCandidacies2(Request $request){

        // print_r($request->all());
        // exit;
         $candidat= JobCandidate::where('email',$request->email)->first();
         //return $candidat;
         $offerCandidacies = new JobOfferCandidacies();
         $offerCandidacies->job_offer = $request->job_offer;
         $offerCandidacies->job_candidate = $candidat->id;
        

         if($request->hasFile('Files')){
            $file=$request->file("Files"); 
            $filename = $request->job_offer.'-'.$candidat->id.'.'.$file->extension();
            $file->storeAs('jobOffersImages',$filename,'public');
            $offerCandidacies->files=json_encode($filename);
                }

         $offerCandidacies->save();


        return response()->json($request->all());

    }


    private function guard()
    {
        return Auth::guard();
    }
}
