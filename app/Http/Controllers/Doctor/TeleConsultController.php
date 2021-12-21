<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Patient;
use App\Meeting;
use Carbon\Carbon;
class TeleConsultController extends Controller
{
	public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }

    public function index(Request $request) {
    	$user = Session::get('auth');
    	$keyword = $request->view_all ? '' : $request->date_range;
        $data = Meeting::select(
        	"meetings.*",
        	"meetings.id as meetID",
        	"pat.*"
        )->leftJoin("patients as pat", "meetings.patient_id", "=", "pat.id");
        if($keyword){
        	$date_start = date('Y-m-d',strtotime(explode(' - ',$request->date_range)[0]));
            $date_end = date('Y-m-d',strtotime(explode(' - ',$request->date_range)[1]));
            $data = $data
                ->where(function($q) use($date_start, $date_end) {
                $q->whereBetween('meetings.date_meeting', [$date_start, $date_end]);
            });
        }
        $data = $data->where("meetings.doctor_id","=", $user->id)
        		->whereDate("meetings.date_meeting", ">=", Carbon::now()->toDateString())
        		->orderBy('meetings.date_meeting', 'asc')
        		->paginate(20);
    	$patients =  Patient::select(
            "patients.*",
            "user.email as email"
        ) ->leftJoin("users as user","patients.account_id","=","user.id")
         ->where('patients.doctor_id',$user->id)
        ->get();

        $keyword_past = $request->view_all_past ? '' : $request->date_range_past;
        $data_past = Meeting::select(
        	"meetings.*",
        	"meetings.id as meetID",
        	"pat.*"
        )->leftJoin("patients as pat", "meetings.patient_id", "=", "pat.id");
        if($keyword_past){
        	$date_start = date('Y-m-d',strtotime(explode(' - ',$request->date_range_past)[0]));
            $date_end = date('Y-m-d',strtotime(explode(' - ',$request->date_range_past)[1]));
            $data_past = $data_past
                ->where(function($q) use($date_start, $date_end) {
                $q->whereBetween('meetings.date_meeting', [$date_start, $date_end]);
            });
        }
        $data_past = $data_past->where("meetings.doctor_id","=", $user->id)
        		->whereDate("meetings.date_meeting", "<", Carbon::now()->toDateString())
        		->orderBy('meetings.date_meeting', 'desc')
        		->paginate(20);

        return view('doctors.teleconsult',[
            'patients' => $patients,
            'search' => $keyword,
            'data' => $data,
            'pastmeetings' => $data_past,
            'search_past' => $keyword_past
        ]);
    }

    public function storeMeeting(Request $req) {
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
		$toInvite = explode('|', $req->email);
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
		  "sendEmail": '.$req->sendemail.',
		  "title": "'.$title.'",
		  "start": "'.$start.'",
		  "end": "'.$end.'",
		  "timezone": "Asia/Manila",
		  "invitees": [
		    {
		      "email": "'.$toInvite[1].'",
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
            'patient_id' => $toInvite[0],
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

        Session::put("action_made","Successfully added new meeting");
        Meeting::create($data);
    }

    public function validateDateTime(Request $req) {
        $user = Session::get('auth');
    	$date = Carbon::parse($req->date)->format('Y-m-d');
    	$time = Carbon::parse($req->time)->format('H:i:s');
        $doctor_id = $req->doctor_id ? $req->doctor_id : $user->id;
    	$endtime = Carbon::parse($time)
		            ->addMinutes($req->duration)
		            ->format('H:i:s');
    	// $meetings = Meeting::whereDate('date_meeting','=', $date)
    	// 					->whereTime('from_time', '<=', $time)
    	// 					->whereTime('to_time', '>=', $time)
    	// 					->orWhereTime('from_time', '<=', $endtime)
    	// 					->whereTime('to_time', '>=', $endtime)
    	// 					->orWhereTime('from_time', '>=', $time)
    	// 					->whereTime('to_time', '<=', $time)
    	// 					->orWhereTime('from_time', '>=', $endtime)
    	// 					->whereTime('to_time', '<=', $endtime)
    	// 					->count();
		$meetings = Meeting::whereDate('date_meeting','=', $date)->where('doctor_id', $doctor_id)->get();
		$count = 1;
        if($date === Carbon::now()->format('Y-m-d') && $time <= Carbon::now()->format('H:i:s')) {
            return $count;
        }
		foreach ($meetings as $meet) {
			if(($time >= $meet->from_time && $time <= $meet->to_time) || ($endtime >= $meet->from_time && $endtime <= $meet->to_time) || ($meet->from_time >= $time && $meet->to_time <= $endtime) || ($meet->from_time >= $time && $meet->to_time <= $endtime)) {
				
				return $meet->count();
			}
		}
    }

    public function meetingInfo(Request $req) {
    	$meeting = Meeting::select(
    		"meetings.*",
    		"pat.*",
    		"meetings.id as meetID"
    	)->leftJoin("patients as pat","pat.id","=","meetings.patient_id")
         ->where('meetings.id',$req->meet_id)
        ->first();

    	return json_encode($meeting);
    }

    public function indexCall($id) {
    	$meetings = Meeting::select(
    		"meetings.*",
    		"pat.*",
    		"meetings.id as meetID"
    	)->leftJoin("patients as pat","pat.id","=","meetings.patient_id")
         ->where('meetings.id',$id)
        ->first();
        $case_no = mt_rand(100000000, 999999999);

        return view('doctors.teleCall',[
        	'meeting' => $meetings,
            'case_no' => $case_no
        ]);
    }

    public function storeToken(Request $req) {
        $envKey = 'WEBEX_API';
        $envValue = $req->webextoken;
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = env($envKey);

        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}\n", $str);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
}
