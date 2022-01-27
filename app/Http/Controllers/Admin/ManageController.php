<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Facility;
use App\Barangay;
use App\Patient;
use App\User;
use App\Countries;
use App\Region;
use App\MunicipalCity;
use App\Province;
use App\PendingMeeting;
use Carbon\Carbon;
use App\ClinicalHistory;
use App\CovidAssessment;
use App\CovidScreening;
class ManageController extends Controller
{
	public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }
    
    public function AdminFacility() {
    	$user = Session::get('auth');
    	$facility = Facility::find($user->facility_id);
    	$province = Province::all();
    	return view('admin.facility',[
            'facility' => $facility,
            'province' => $province
        ]);
    }

    public function updateFacility(Request $req) {
    	Facility::find($req->id)->update($req->all());
    	Session::put("action_made","Successfully updated facility");
    }

    public function patientList(Request $request)
    {
        $user = Session::get('auth');

        $municity =  MunicipalCity::where('prov_psgc', $user->facility->province->prov_psgc)->get();
        $province = Province::all();
        if($request->view_all == 'view_all')
            $keyword = '';
        else{
            if(Session::get("keyword")){
                if(!empty($request->keyword) && Session::get("keyword") != $request->keyword)
                    $keyword = $request->keyword;
                else
                    $keyword = Session::get("keyword");
            } else {
                $keyword = $request->keyword;
            }
        }
        Session::put('keyword',$keyword);
        $data = Patient::select(
            "patients.*",
            "bar.brg_name as barangay",
            "user.email as email",
            "user.username as username",
        ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
        ->leftJoin("users as user","user.id","=","patients.account_id")
        ->where('patients.doctor_id', $user->id)
        ->where(function($q) use ($keyword){
            $q->where('patients.fname',"like","%$keyword%")
                ->orwhere('patients.lname',"like","%$keyword%")
                ->orwhere('patients.mname',"like","%$keyword%");
               
            })
            ->orderby('patients.lname','asc')
            ->paginate(30);

        $patients = Patient::select(
            "patients.*",
            "bar.brg_name as barangay",
            "user.email as email",
            "user.username as username",
        ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
        ->leftJoin("users as user","user.id","=","patients.account_id")
        ->where('patients.doctor_id', $user->id)->get();

        $doctors = User::where('level', 'doctor')
                       ->where('facility_id', $user->facility_id)
                       ->get();
        $nationality = Countries::orderBy('nationality', 'asc')->get();
        $region = Region::all();
        $nationality_def = Countries::where('num_code', '608')->first();
        return view('admin.patient',[
            'data' => $data,
            'municity' => $municity,
            'patients' => $patients,
            'users' => $doctors,
            'nationality' => $nationality,
            'nationality_def' => $nationality_def,
            'region' => $region,
            'province' => $province,
            'user' => $user
        ]);
    }

    public function schedTeleStore(Request $req) {
        $date = date('Y-m-d', strtotime($req->date_from));
        $req->request->add([
            'status' => 'Pending',
            'datefrom' => $date
        ]);
        if($req->meeting_id) {
            PendingMeeting::find($req->meeting_id)->update($req->except('meeting_id', 'facility_id', 'date_from'));
        } else {
            PendingMeeting::create($req->except('meeting_id', 'facility_id', 'date_from'));
        }
        Session::put("action_made","Please wait for the confirmation of doctor.");
    }

    public function meetingInfo(Request $req) {
        $meeting = PendingMeeting::find($req->meet_id);
        return json_encode($meeting);
    }
    public function clinical($id) {
        $patient = Patient::find($id);
        $facility = Facility::orderBy('facilityname', 'asc')->get();
        $date_referral = '';
        $date_onset_illness = '';
        if($patient->clinical) {
            $date_referral = date('m/d/Y', strtotime($patient->clinical->date_referral));
            $date_onset_illness = date('m/d/Y', strtotime($patient->clinical->date_onset_illness));
        }
        return view('admin.clinical',[
            'patient' => $patient,
            'facility' => $facility,
            'date_referral' => $date_referral,
            'date_onset_illness' => $date_onset_illness
        ]);
    }
    public function clinicalStore(Request $req) {
       $date_illness = date('Y-m-d', strtotime($req->date_onset_illness));
       $date_referral = date('Y-m-d', strtotime($req->date_referral));
       $data = $req->all();
       $data['date_onset_illness'] = $date_illness;
       $data['date_referral'] = $date_referral;
       if($req->id) {
        ClinicalHistory::find($req->id)->update($data);
        Session::put("action_made","Successfully Update Clinical History and Physical Exam");
       } else {
        ClinicalHistory::create($data);
        Session::put("action_made","Successfully Created Clinical History and Physical Exam");
       }
    }
    public function covid($id) {
        $patient = Patient::find($id);
        $countries = Countries::orderBy('en_short_name', 'asc')->get();
        $date_departure = '';
        $date_arrival_ph = '';
        $date_contact_known_covid_case = '';
        $acco_date_last_expose = '';
        $food_es_date_last_expose = '';
        $store_date_last_expose = '';
        $fac_date_last_expose = '';
        $event_date_last_expose = '';
        $wp_date_last_expose = '';
        $list_name_occasion = [];
        $days_14_date_onset_illness = '';
        $referral_date = '';
        $xray_date = '';
        $date_collected = '';
        $date_sent_ritm = '';
        $date_received_ritm = '';
        $scrum = [];
        $oro_naso_swab = [];
        $spe_others = [];
        if($patient->covidscreen) {
            $date_departure = $patient->covidscreen->date_departure ? date('m/d/Y', strtotime($patient->covidscreen->date_departure)) : '';
            $date_arrival_ph = $patient->covidscreen->date_arrival_ph ? date('m/d/Y', strtotime($patient->covidscreen->date_arrival_ph)) : '';
            $date_contact_known_covid_case = $patient->covidscreen->date_contact_known_covid_case ? date('m/d/Y', strtotime($patient->covidscreen->date_contact_known_covid_case)) : '';
            $acco_date_last_expose = $patient->covidscreen->acco_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->acco_date_last_expose)) : '';
            $food_es_date_last_expose = $patient->covidscreen->food_es_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->food_es_date_last_expose)) : '';
            $store_date_last_expose = $patient->covidscreen->store_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->store_date_last_expose)) : '';
            $fac_date_last_expose = $patient->covidscreen->fac_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->fac_date_last_expose)) : '';
            $event_date_last_expose = $patient->covidscreen->event_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->event_date_last_expose)) : '';
            $wp_date_last_expose = $patient->covidscreen->wp_date_last_expose ? date('m/d/Y', strtotime($patient->covidscreen->wp_date_last_expose)) : '';
            $list_name_occasion = $patient->covidscreen->list_name_occasion ? explode("|",$patient->covidscreen->list_name_occasion) : [];
        }
        if($patient->covidassess) {
            $days_14_date_onset_illness = $patient->covidassess->days_14_date_onset_illness ? date('m/d/Y', strtotime($patient->covidassess->days_14_date_onset_illness)) : '';
            $referral_date = $patient->covidassess->referral_date ? date('m/d/Y', strtotime($patient->covidassess->referral_date)) : '';
            $xray_date = $patient->covidassess->xray_date ? date('m/d/Y', strtotime($patient->covidassess->xray_date)) : '';
            $date_collected = $patient->covidassess->date_collected ? date('m/d/Y', strtotime($patient->covidassess->date_collected)) : '';
            $date_sent_ritm = $patient->covidassess->date_sent_ritm ? date('m/d/Y', strtotime($patient->covidassess->date_sent_ritm)) : '';
            $date_received_ritm = $patient->covidassess->date_received_ritm ? date('m/d/Y', strtotime($patient->covidassess->date_received_ritm)) : '';
            $scrum = $patient->covidassess->scrum ? explode("|",$patient->covidscreen->scrum) : [];
            $oro_naso_swab = $patient->covidassess->oro_naso_swab ? explode("|",$patient->covidscreen->oro_naso_swab) : [];
            $spe_others = $patient->covidassess->spe_others ? explode("|",$patient->covidscreen->spe_others) : [];
        }
        return view('admin.covid',[
            'patient' => $patient,
            'countries' => $countries,
            'date_departure' => $date_departure,
            'date_arrival_ph' => $date_arrival_ph,
            'date_contact' => $date_contact_known_covid_case,
            'acco_date_last_expose' => $acco_date_last_expose,
            'food_es_date_last_expose' => $food_es_date_last_expose,
            'store_date_last_expose' => $store_date_last_expose,
            'fac_date_last_expose' => $fac_date_last_expose,
            'event_date_last_expose' => $event_date_last_expose,
            'wp_date_last_expose' => $wp_date_last_expose,
            'list_name_occasion' => $list_name_occasion,
            'days_14_date_onset_illness' => $days_14_date_onset_illness,
            'referral_date' => $referral_date,
            'xray_date' => $xray_date,
            'date_collected' => $date_collected,
            'date_sent_ritm' => $date_sent_ritm,
            'date_received_ritm' => $date_received_ritm,
            'scrum' => $scrum,
            'oro_naso_swab' => $oro_naso_swab,
            'spe_others' => $spe_others
        ]);
    }

    public function covidStore(Request $req) {
        $list_name_occasion = implode('|', $req->list_name_occa);
        $req->request->add([
            'list_name_occasion' =>  $list_name_occasion
        ]);
        $data = $req->all();
        $data['date_departure'] = $req->date_departure ? date('Y-m-d', strtotime($req->date_departure)) : null;
        $data['date_arrival_ph'] = $req->date_arrival_ph ? date('Y-m-d', strtotime($req->date_arrival_ph)) : null;
        $data['date_contact_known_covid_case'] = $req->date_contact_known_covid_case ? date('Y-m-d', strtotime($req->date_contact_known_covid_case)) : null;
        $data['acco_date_last_expose'] = $req->acco_date_last_expose ? date('Y-m-d', strtotime($req->acco_date_last_expose)) : null;
        $data['food_es_date_last_expose'] = $req->food_es_date_last_expose ? date('Y-m-d', strtotime($req->food_es_date_last_expose)) : null;
        $data['store_date_last_expose'] = $req->store_date_last_expose ? date('Y-m-d', strtotime($req->store_date_last_expose)) : null;
        $data['fac_date_last_expose'] = $req->fac_date_last_expose ? date('Y-m-d', strtotime($req->fac_date_last_expose)) : null;
        $data['event_date_last_expose'] = $req->event_date_last_expose ? date('Y-m-d', strtotime($req->event_date_last_expose)) : null;
        $data['wp_date_last_expose'] = $req->wp_date_last_expose ? date('Y-m-d', strtotime($req->wp_date_last_expose)) : null;
        $screenid = $req->screen_id;
        $assessid = $req->assess_id;
        unset($data['screen_id']);
        unset($data['assess_id']);
        unset($data['list_name_occa']);
        if($screenid) {
            CovidScreening::find($screenid)->update($data);
            Session::put("action_made","Successfully Update Covid-19 Screening");
        } else {
            CovidScreening::create($data);
            Session::put("action_made","Successfully Created Covid-19 Screening");
        }
    }
    public function diagnosis($id) {
    }
    public function plan($id) {
    }
}
