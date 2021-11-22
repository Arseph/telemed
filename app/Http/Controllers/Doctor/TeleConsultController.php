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
    public function index(Request $request) {
    	$user = Session::get('auth');
    	$keyword = $request->date_range;
        $data = Meeting::select(
        	"meetings.*",
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
        $data = $data->where("meetings.doctor_id","=", $user->id)->paginate(20);
    	$patients =  Patient::select(
            "patients.*",
            "user.email as email"
        ) ->leftJoin("users as user","patients.account_id","=","user.id")
         ->where('patients.doctor_id',$user->id)
        ->get();
        return view('doctors.teleconsult',[
            'patients' => $patients,
            'data' => $data
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
		$patients = explode('|', $req->email);
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
		      "email": "'.$patients[1].'",
		      "displayName": "Patient",
		      "coHost": false
		    }
		  ]
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer OGNjZDE2YjEtMmUzMS00ODQ4LTgwMTMtMzFjOWNkM2QwZDg1NTQwMmUwYTMtNDVh_P0A1_caaa8419-6ffd-4ddb-8fb5-d89f059408c4',
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		$meet = json_decode($response,true);
		curl_close($curl);
		$data = array(
            'doctor_id' => $user->id,
            'patient_id' => $patients[0],
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
    	$date = Carbon::parse($req->date)->format('Y-m-d');
    	$time = Carbon::parse($req->time)->format('H:i:s');
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
		$meetings = Meeting::whereDate('date_meeting','=', $date)->get();
		$count = 0;
		foreach ($meetings as $meet) {
			if(($time >= $meet->from_time && $time <= $meet->to_time) || ($endtime >= $meet->from_time && $endtime <= $meet->to_time) || ($meet->from_time >= $time && $meet->to_time <= $endtime) || ($meet->from_time >= $time && $meet->to_time <= $endtime)) {
				
				return $meet->count();
			}
		}
    }
}
