<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facility;
use App\Barangay;
use App\Patient;
use App\User;
use App\Countries;
use App\Region;
use App\MunicipalCity;
use App\Province;
use App\Meeting;
use Carbon\Carbon;
use App\PendingMeeting;
class PatientController extends Controller
{
     public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }
    
    public function patientList(Request $request)
    {
        $user = Session::get('auth');
        $municity =  MunicipalCity::all();
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

        $requested = Patient::select(
            "patients.*",
            "bar.brg_name as barangay",
            "user.email as email",
            "user.username as username",
        ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
        ->leftJoin("users as user","user.id","=","patients.account_id")
        ->where('patients.doctor_id', $user->id)
        ->where('patients.is_accepted', 0)
        ->get();

        $patients = Patient::select(
            "patients.*",
            "bar.brg_name as barangay",
            "user.email as email",
            "user.username as username",
        ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
        ->leftJoin("users as user","user.id","=","patients.account_id")
        ->where('patients.doctor_id', $user->id)->get();
        $users = User::all();
        $nationality = Countries::orderBy('nationality', 'asc')->get();
        $region = Region::all();
        $nationality_def = Countries::where('num_code', '608')->first();
        return view('doctors.patient',[
            'data' => $data,
            'requested' => $requested,
            'municity' => $municity,
            'patients' => $patients,
            'users' => $users,
            'nationality' => $nationality,
            'nationality_def' => $nationality_def,
            'region' => $region,
            'user' => $user,
            'province' => $province
        ]);
    }

    public function patientUpdate(Request $req)
    {
       
        $user = Session::get('auth');

        $municity =  Facility::select(
            "facilities.*",
            "prov.prov_name as province",
            "prov.prov_psgc as p_id",
            "mun.muni_name as muncity",
            "mun.muni_psgc as m_id",
            "bar.brg_name as barangay",
            "bar.brg_psgc as b_id",
        ) ->leftJoin("provinces as prov","prov.prov_psgc","=","facilities.prov_psgc")
         ->leftJoin("municipal_cities as mun","mun.muni_psgc","=","facilities.muni_psgc")
         ->leftJoin("barangays as bar","bar.brg_psgc","=","facilities.brgy_psgc")
         ->where('facilities.id',$user->facility_id)
        ->get();

        return view('doctors.patient_body',[
            'municity' => $municity
        ]);
    }

    public function getBaranggays($muncity_id)
    {
        $brgy = Barangay::where('muni_psgc',$muncity_id)
        ->orderBy('brg_name','asc')
        ->get();
        return $brgy;
    }

    public function storePatient(Request $req) {
        $user = Session::get('auth');
        $doctor_id = $req->doctor_id ? $req->doctor_id : $user->id;
        $province = Facility::select(
            "facilities.*",
            "prov.prov_psgc as p_id",
        ) ->leftJoin("provinces as prov","prov.prov_psgc","=","facilities.prov_psgc")
        ->where('facilities.id',$user->facility_id)
        ->first();
        $subcat = Patient::find($req->patient_id);
        $unique_id = $req->fname.' '.$req->mname.' '.$req->lname.mt_rand(1000000, 9999999);
        $data = array(
            'unique_id' => $unique_id,
            'doctor_id' => $doctor_id,
            'facility_id' => $user->facility_id,
            'phic_status' => $req->phic_status,
            'phic_id' => $req->phic_id,
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'occupation' => $req->occupation,
            'nationality_id' => $req->nationality_id,
            'id_type' => $req->id_type,
            'id_type_no' => $req->id_type_no,
            'contact' => $req->contact,
            'dob' => $req->dob,
            'sex' => $req->sex,
            'civil_status' => $req->civil_status,
            'region' => $req->region,
            'house_no' => $req->house_no,
            'street' => $req->street,
            'muncity' => $req->muncity,
            'province' => $province->p_id,
            'brgy' => $req->brgy,
            'address' => $req->address,
            'tsekap_patient' => 0,
            'is_accepted' => 0,
            'religion' => $req->religion,
            'edu_attain' => $req->edu_attain
        );
        if($req->patient_id){
            Session::put("action_made","Successfully updated Patient");
            $patient = Patient::find($req->patient_id);
            $patient->update($data);
            if($req->email && $req->username && $req->password) {
                $data = array(
                    'fname' => $req->fname,
                    'mname' => $req->mname,
                    'lname' => $req->lname,
                    'level' => 'patient',
                    'facility_id' => $user->facility_id,
                    'status' => 'active',
                    'contact' => $req->contact,
                    'email' => $req->email,
                    'username' => $req->username,
                    'password' => bcrypt($req->password)
                );
                $account = User::find($patient->account_id);
                if($account) {
                    $account->update($data);
                } else {
                    $account = User::create($data);
                    Patient::find($account->id)->update([
                        'account_id' => $account->id
                    ]);
                }
            }
        }
        else{
            Session::put("action_made","Successfully added new Patient");
            $patient = Patient::create($data);
            if($req->email && $req->username && $req->password) {
                $data = array(
                    'fname' => $req->fname,
                    'mname' => $req->mname,
                    'lname' => $req->lname,
                    'level' => 'patient',
                    'facility_id' => $user->facility_id,
                    'status' => 'active',
                    'contact' => $req->contact,
                    'email' => $req->email,
                    'username' => $req->username,
                    'password' => bcrypt($req->password)
                );
                $account = User::find($patient->account_id);
                if($account) {
                    $account->update($data);
                } else {
                    $account = User::create($data);
                    Patient::find($patient->id)->update([
                        'account_id' => $account->id
                    ]);
                }
            }
        }
    }

    public function deletePatient($id) {
        $patient = Patient::find($id);
        $account = User::find($patient->account_id);
        if($patient) {
            $patient->delete();
        }
        if($account) {
            $account->delete();
        }
        Session::put("delete_action","Successfully delete Patient");
    }

    public function createPatientAcc(Request $req) {
        $user = Session::get('auth');
        $data = array(
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'level' => 'patient',
            'facility_id' => $user->facility_id,
            'status' => 'active',
            'contact' => $req->contact,
            'email' => $req->email,
            'username' => $req->username,
            'password' => bcrypt($req->password)
        );
        Session::put("action_made","Successfully created account");
        $user = User::create($data);
        $accountID = $user->id;
        Patient::find($req->account_id)->update([
            'account_id' => $accountID
        ]);
    }

    public function acceptPatient($id, Request $req) {
        $user = Session::get('auth');
        $curl = curl_init();
        $title = $req->title;
        $date = date('Y-m-d', strtotime($req->datefrom));
        $time = date('H:i:s', strtotime($req->time));
        $endtime = Carbon::parse($time)
                            ->addMinutes($req->duration)
                            ->format('H:i:s');
        $start = $date.'T'.$time.'+08:00';
        $end = $date.'T'.$endtime.'+08:00';
        $email = $req->email;
        $sendemail = $req->sendemail;
        $patient_id = $req->patient_meeting_id;
        if($req->respo > 0) {
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://webexapis.com/v1/meetings',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
              "enabledAutoRecordMeeting": true,
              "allowAnyUserToBeCoHost": false,
              "enabledJoinBeforeHost": false,
              "enableConnectAudioBeforeHost": false,
              "excludePassword": false,
              "publicMeeting": false,
              "enableAutomaticLock": false,
              "allowFirstUserToBeCoHost": false,
              "allowAuthenticatedDevices": false,
              "sendEmail": '.$sendemail.',
              "title": "'.$title.'",
              "start": "'.$start.'",
              "end": "'.$end.'",
              "timezone": "Asia/Manila",
              "invitees": [
                {
                  "email": "'.$email.'",
                  "displayName": "Patient",
                  "coHost": false
                }
              ]
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('WEBEX_API').'',
                'Content-Type: application/json'
              ),
            ));

            $response = curl_exec($curl);
            $meet = json_decode($response,true);
            curl_close($curl);
            $data = array(
                'doctor_id' => $user->id,
                'patient_id' => $patient_id,
                'date_meeting' => $date,
                'from_time' => $time,
                'to_time' => $endtime,
                'meeting_id' => $meet['id'],
                'meeting_number' => $meet['meetingNumber'],
                'title' => $meet['title'],
                'password' => $meet['password'],
                'phone_video_password' => $meet['phoneAndVideoSystemPassword'],
                'meeting_type' => $meet['meetingType'],
                'state' => $meet['state'],
                'timezone' => $meet['timezone'],
                'start' => $meet['start'],
                'end' => $meet['end'],
                'host_user_id' => $meet['hostUserId'],
                'host_display_name' => $meet['hostDisplayName'],
                'host_email' => $meet['hostEmail'],
                'host_key' => $meet['hostKey'],
                'site_url' => $meet['siteUrl'],
                'web_link' => $meet['webLink'],
                'sip_address' => $meet['sipAddress'],
                'dial_in_ip_address' => $meet['dialInIpAddress'],
                'enable_auto_record_meeting' => $meet['enabledAutoRecordMeeting'],
                'allow_authenticate_device' => $meet['allowAuthenticatedDevices'],
                'enable_join_before_host' => $meet['enabledJoinBeforeHost'],
                'join_before_host_meeting' => $meet['joinBeforeHostMinutes'],
                'enable_connect_audio_before_host' => $meet['enableConnectAudioBeforeHost'],
                'exclude_password' => $meet['excludePassword'],
                'public_meeting' => $meet['publicMeeting'],
                'enable_automatic_lock' => $meet['enableAutomaticLock']
            );
            $meeting_approved = Meeting::create($data);
            $meeting = PendingMeeting::find($id)->update([
                'meet_id' => $meeting_approved->id
            ]);
        }
        $patient = Patient::find($patient_id)->update([
            'is_accepted' => $req->respo
        ]);
        if($req->respo > 0) {
            $action = "Successfully accept patient.\n Successfully added new teleconsultation";
        } else {
            $action = "Successfully decline patient";
        }
        Session::put("action_made", $action);
    }

    public function patientConsultInfo($id) {
        $info = Patient::find($id)->meeting;
        return json_encode($info);
    }

    public function patientInformation($id) {
        try {
            $decid = Crypt::decrypt($id);
            $patient = Patient::find($decid);
            $municity =  MunicipalCity::all();
            $nationality_def = Countries::where('num_code', '608')->first();
            $nationality = Countries::orderBy('nationality', 'asc')->get();
            return view('doctors.patientinfo',[
                'patient' => $patient,
                'nationality_def' => $nationality_def,
                'nationality' => $nationality,
                'municity' => $municity
            ]);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
