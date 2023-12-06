<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Lead;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function rubriques()
    {
        $rubriques = Depense::rubriques();
        return response()->json($rubriques);
    }


    static function traitement($depenses)
    {
        $data = array();
        $user = User::find(Auth::user()->id);
        $is_admin = $user->role->Alias == 'admin' ? true : false;

        foreach ($depenses as $depense) {
            $item = null;
            $manager = null;
            $label = null;

            if ($depense->intervention_lead_id) {
                $item = Lead::find($depense->leadIntervention->leads_id);
                $manager = User::find($depense->leadIntervention->sales_id);
                $label = $depense->leadIntervention->label;
            } else {
                $item = School::find($depense->schoolIntevention->school_id);
                $manager = User::find($depense->schoolIntevention->responsable_id);
                $label = $depense->schoolIntevention->label;
            }

            if ($item) {
                $data[] = array(
                    'id' => $depense->id,
                    'school_id' => $item->id,
                    'school_name' => $item->name,
                    'school_logo' => $item->getLogo(),
                    'interv_label' => $label,
                    'cs' => $manager->getPicture(),
                    'user' => $depense->user,
                    'createdAt' => date('d-m-Y H:i', strtotime($depense->created_at)) . 'min',
                    'rubriques' => explode(',', $depense->rubriques),
                    'amounts' => explode(',', $depense->amounts),
                    'total' => array_sum(explode(',', $depense->amounts)),
                    'state' => $depense->validateBy  ? 'validée par ' . User::find($depense->validateBy)->getNomComplet() : 'non-validée',
                    'admin' => $is_admin,
                    'createdBy' => $user->nom . ' ' . $user->prenom == $depense->user ? true : false,
                );
            }
        }
        return $data;
    }
    public function depenses()
    {
        // $depenses = Depense::where('intervention_id', '!=', null)->get();
        $depenses = Depense::all();
        return response()->json(self::traitement($depenses));
    }
    public function depensesLeads()
    {
        $depenses = Depense::where('intervention_lead_id', '!=', null)->get();
        return response()->json(self::traitement($depenses));
    }

    public function getDepense($id)
    {
        $depense = Depense::find($id);
        $data = array(
            'id' => $depense->id,
            'amounts' => explode(',', $depense->amounts),
            'rubriques' => explode(',', $depense->rubriques),
            'total' => array_sum(explode(',', $depense->amounts))
        );
        return response()->json($data);
    }

    function validatTheRequest($id)
    {
        $depense = Depense::find($id);
        $depense->validateBy = Auth::user()->id;
        $depense->validationDate = date('Y-m-d H:i:s');
        $depense->save();
    }

    public function filter($need, $state = null)
    {
        if ($state) {
            $depenses = Depense::join('lead_intervs', 'lead_intervs.id', 'depenses.intervention_lead_id')->where('lead_intervs.sales_id', $need)->get();
        } else {
            $depenses = Depense::join('school_interventions', 'school_interventions.id', 'depenses.intervention_id')->where('school_interventions.responsable_id', $need)->get();
        }
        return response()->json(self::traitement($depenses));
    }
    public function filter_rubrique($need,$lead = null)
    {
        if($lead){
            $depenses = Depense::where([['rubriques', 'LIKE', '%' . $need . '%'],['intervention_lead_id', '!=', null]])->get();
        }else{
            $depenses = Depense::where([['rubriques', 'LIKE', '%' . $need . '%'],['intervention_id', '!=', null]])->get();
        }
        return response()->json(self::traitement($depenses));
    }

    public function updateDepense(Request $request)
    {
        $depense = Depense::find($request->id);
        $depense->rubriques = implode(',', $request->rubrique);
        $depense->amounts = implode(',', $request->amount);
        $depense->save();
        return response()->json(array('rubriques' => $request->rubrique, 'amounts' => $request->amount, 'total' => array_sum($request->amount)));
    }
}
