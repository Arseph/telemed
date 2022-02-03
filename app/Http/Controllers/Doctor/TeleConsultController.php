<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Patient;
use App\Meeting;
use Carbon\Carbon;
use App\PendingMeeting;
use App\Facility;
use App\TeleCategory;
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
            "meetings.user_id as Creator",
            "meetings.doctor_id as RequestTo",
        	"pat.lname as patLname",
            "pat.fname as patFname",
            "pat.mname as patMname",
        )->leftJoin("patients as pat", "meetings.patient_id", "=", "pat.id");
        if($keyword){
        	$date_start = date('Y-m-d',strtotime(explode(' - ',$request->date_range)[0]));
            $date_end = date('Y-m-d',strtotime(explode(' - ',$request->date_range)[1]));
            $data = $data
                ->where(function($q) use($date_start, $date_end) {
                $q->whereBetween('meetings.date_meeting', [$date_start, $date_end]);
            });
        }
        $data = $data->where(function($q) use($user){
            $q->where("meetings.doctor_id","=", $user->id)
            ->orWhere("meetings.user_id", "=", $user->id);
            })->whereDate("meetings.date_meeting", ">=", Carbon::now()->toDateString())
        		->orderBy('meetings.date_meeting', 'asc')
        		->paginate(20);
    	$patients =  Patient::select(
            "patients.*",
            "user.email as email"
        ) ->leftJoin("users as user","patients.account_id","=","user.id")
         ->where('patients.facility_id',$user->facility_id)
        ->get();

        $keyword_past = $request->view_all_past ? '' : $request->date_range_past;
        $data_past = Meeting::select(
        	"meetings.*",
        	"meetings.id as meetID",
        	"pat.lname as patLname",
            "pat.fname as patFname",
            "pat.mname as patMname",
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

        $keyword_req = $request->view_all_req ? '' : $request->date_range_req;
        $data_req = PendingMeeting::select(
            "pending_meetings.*",
            "pending_meetings.id as meetID",
            "pending_meetings.created_at as reqDate",
            "pat.lname as patLname",
            "pat.fname as patFname",
            "pat.mname as patMname",
        )->leftJoin("patients as pat", "pending_meetings.patient_id", "=", "pat.id");
        if($keyword_req){
            $date_start = date('Y-m-d',strtotime(explode(' - ',$request->date_range_req)[0]));
            $date_end = date('Y-m-d',strtotime(explode(' - ',$request->date_range_req)[1]));
            $data_req = $data_req
                ->where(function($q) use($date_start, $date_end) {
                $q->whereDate('pending_meetings.datefrom', '>=', $date_start);
                $q->whereDate('pending_meetings.datefrom', '<=', $date_end);
            });
        }
        $status_req = $request->view_all_req ? '' : $request->status_req;
        $active_tab = $request->active_tab ? $request->active_tab : 'upcoming';
        if($status_req) {
            $data_req = $data_req->where(function($q) use($status_req) {
                $q->where('pending_meetings.status', $status_req);
            });
        }
        $data_req = $data_req->where("pending_meetings.doctor_id","=", $user->id)
                ->orderBy('pending_meetings.id', 'desc')
                ->paginate(20);

        $data_my_req = PendingMeeting::select(
            "pending_meetings.*",
            "pending_meetings.id as meetID",
            "pending_meetings.created_at as reqDate",
            "pat.lname as patLname",
            "pat.fname as patFname",
            "pat.mname as patMname",
        )->leftJoin("patients as pat", "pending_meetings.patient_id", "=", "pat.id")
        ->where("pending_meetings.user_id","=", $user->id)
                ->orderBy('pending_meetings.id', 'desc')
                ->paginate(10);
        $facilities = Facility::orderBy('facilityname', 'asc')->get();
        $count_req = PendingMeeting::select(
            "pending_meetings.*",
            "pending_meetings.id as meetID",
            "pending_meetings.created_at as reqDate",
        )->leftJoin("patients as pat", "pending_meetings.patient_id", "=", "pat.id")
        ->where('pending_meetings.status', 'Pending')
        ->where("pending_meetings.doctor_id","=", $user->id)->count();
        $telecat = TeleCategory::orderBy('category_name', 'asc')->get();
        return view('doctors.teleconsult',[
            'patients' => $patients,
            'search' => $keyword,
            'data' => $data,
            'pastmeetings' => $data_past,
            'search_past' => $keyword_past,
            'facilities' => $facilities,
            'search_req' => $keyword_req,
            'data_req' => $data_req,
            'status_req' => $status_req,
            'active_tab' => $active_tab,
            'data_my_req' => $data_my_req,
            'active_user' => $user,
            'pending' => $count_req,
            'telecat' => $telecat
        ]);
    }

    public function validateDateTime(Request $req) {
        $user = Session::get('auth');
    	$date = Carbon::parse($req->date)->format('Y-m-d');
    	$time = $req->time ? Carbon::parse($req->time)->format('H:i:s') : '';
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
        if($date === Carbon::now()->format('Y-m-d') && $time <= Carbon::now()->addMinutes('180')->format('H:i:s') && $time) {
            return 'Not valid';
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
    		"meetings.id as meetID",
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

    public function getPendingMeeting($id) {
        $pend_meet = PendingMeeting::find($id);
        $encoded = $pend_meet->encoded->facility;
        $patient = $pend_meet->patient;
        return response()->json($pend_meet);
    }

    public function acceptDeclineMeeting($id, Request $req) {
        $user = Session::get('auth');
        $meet = PendingMeeting::find($id);
        $action = $req->action;
        $date = date('Y-m-d', strtotime($meet->datefrom));
        $time = date('H:i:s', strtotime($meet->time));
        $endtime = Carbon::parse($time)
                            ->addMinutes($meet->duration)
                            ->format('H:i:s');
        $start = $date.'T'.$time.'+08:00';
        $end = $date.'T'.$endtime.'+08:00';
        $email = $meet->email;
        if($action == 'Accept') {
            $curl = curl_init();
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
              "sendEmail": '.$meet->sendemail.',
              "title": "'.$meet->title.'",
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
            $meetres = json_decode($response,true);
            curl_close($curl);
            $data = array(
                'user_id' => $meet->user_id,
                'doctor_id' => $user->id,
                'patient_id' => $meet->patient_id,
                'date_meeting' => $date,
                'from_time' => $time,
                'to_time' => $endtime,
                'meeting_id' => $meetres['id'],
                'meeting_number' => $meetres['meetingNumber'],
                'title' => $meetres['title'],
                'password' => $meetres['password'],
                'phone_video_password' => $meetres['phoneAndVideoSystemPassword'],
                'meeting_type' => $meetres['meetingType'],
                'state' => $meetres['state'],
                'timezone' => $meetres['timezone'],
                'start' => $meetres['start'],
                'end' => $meetres['end'],
                'host_user_id' => $meetres['hostUserId'],
                'host_display_name' => $meetres['hostDisplayName'],
                'host_email' => $meetres['hostEmail'],
                'host_key' => $meetres['hostKey'],
                'site_url' => $meetres['siteUrl'],
                'web_link' => $meetres['webLink'],
                'sip_address' => $meetres['sipAddress'],
                'dial_in_ip_address' => $meetres['dialInIpAddress'],
                'enable_auto_record_meeting' => $meetres['enabledAutoRecordMeeting'],
                'allow_authenticate_device' => $meetres['allowAuthenticatedDevices'],
                'enable_join_before_host' => $meetres['enabledJoinBeforeHost'],
                'join_before_host_meeting' => $meetres['joinBeforeHostMinutes'],
                'enable_connect_audio_before_host' => $meetres['enableConnectAudioBeforeHost'],
                'exclude_password' => $meetres['excludePassword'],
                'public_meeting' => $meetres['publicMeeting'],
                'enable_automatic_lock' => $meetres['enableAutomaticLock']
            );
            $create_meeting = Meeting::create($data);

        }
        $meet_id = $action == 'Accept' ? $create_meeting->id : '';
        $data = array(
            'status' => $action,
            'meet_id' => $meet_id
        );
        $meet->update($data); 
        if($action == 'Accept') {
            Session::put("action_made","Successfully Accept Teleconsultation.");
        } else {
            Session::put("delete_action","Successfully Declined Teleconsultation.");
        }
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
}
