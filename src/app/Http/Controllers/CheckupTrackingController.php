<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Checkup_indicator;
use App\Models\Checkup_tracking;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CheckupTrackingController extends Controller
{

    public static function dates()
    {
        return array(
            date('Y-m-d', strtotime('last week monday')),
            date('Y-m-d', strtotime('last week sunday')),
            date('Y-m-d', strtotime('last week monday',strtotime('last week monday')) ),
            date('Y-m-d', strtotime('last week sunday',strtotime('last week sunday')) ),
        );
    }

    

    public function schools()
    {
        $schools = School::where([['hide_at', 0], ['web_link', '!=', null]])->get();
        $data = array();
        foreach ($schools as $school) {
            if (substr($school->web_link, 0, 1) == 'p') {
                $school_indicators  = json_decode(Http::get('https://boti.education/' . $school->web_link . '/csmapi/7days_indicators'), true);
                $indicators_converted = json_decode(json_encode($school_indicators), true);
                if ($indicators_converted) {
                    foreach ($indicators_converted['indicators'] as $key => $value) {
                        $alias = '7days_' . strtolower($key);
                        $threshold =  Checkup_indicator::select('threshold')->where('alias', $alias)->first();
                        if ($threshold) {
                            $new_tracking         = new Checkup_tracking();
                            $new_tracking->school = $school->id;
                            $new_tracking->value  = $value;
                            $new_tracking->indicator  = '7days_' . strtolower($key);
                            $new_tracking->first_day_week = self::dates()[0];
                            $new_tracking->last_day_week = self::dates()[1];
                            $new_tracking->date = date('Y-m-d H:i:s');
                            $new_tracking->user = Auth::user()->nom . ' ' . Auth::user()->prenom;
                            $new_tracking->save();
                            $data[$school->name][] = [
                                'indicator' => $alias,
                                'value_formated' => strtolower($key) == 'encaissements' || strtolower($key) ==  'depenses' ? number_format($value) . 'DH' : number_format($value),
                                'value' =>  $value,
                                'threshold' => $threshold->threshold
                            ];
                        }
                    }
                }
            }
        }
        return response()->json($data);
    }

    public static function traitement_checkups($date){
        $checkup = Checkup_tracking::where('first_day_week',$date)->limit(1)->first();
        $data = array();
        if ($checkup) {
            $data = array(
                'first'  => Helpers::dateFormat($checkup->first_day_week, '%A %d %b %Y'),
                'last'   => Helpers::dateFormat($checkup->last_day_week, '%A %d %b %Y'),
                'date'   => Helpers::dateFormat($checkup->date, '%A %d %b %Y') . ' ' . date('H:m', strtotime($checkup->date)),
                'user'   => $checkup->user,
                'result' => 'exist'
            );
        } else {
            $data = array('first' => null, 'last' => null, 'result' => 'non-exist', 'date' => null, 'user' => null);
        }

        return $data;
    }
    public function check()
    {
        return response()->json(self::traitement_checkups(self::dates()[0]));
    }
    public function check_two_weeks(){
        return response()->json(self::traitement_checkups(self::dates()[2]));
    }




    public static function traitement($date)
    {
        $data = array();
        $checkups = Checkup_tracking::where('first_day_week', $date)->get();
        foreach ($checkups as $checkup) {
            $school = School::find($checkup->school);
            $alias = Checkup_indicator::where('alias', $checkup->indicator)->first();
            $data[$school->name][] = [
                'indicator' => $checkup->indicator,
                'value_formated' => $checkup->indicator == '7days_encaissements' || $checkup->indicator == '7days_depenses' ? number_format($checkup->value) . 'DH' : number_format($checkup->value),
                'value' => $checkup->value,
                'threshold' =>  $alias->threshold
            ];
        }
        return $data;
    }

    public function last_checkups()
    {
        return response()->json(self::traitement(self::dates()[0]));
    }


    public function two_weeks_ago()
    {
        return response()->json(self::traitement(self::dates()[2]));
    }
}
