<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Patient;
class TeleConsultController extends Controller
{
    public function index() {
    	$user = Session::get('auth');
    	$patients =  Patient::select(
            "patients.*",
            "user.email as email"
        ) ->leftJoin("users as user","patients.account_id","=","user.id")
         ->where('patients.doctor_id',$user->id)
        ->get();
        return view('doctors.teleconsult',[
            'patients' => $patients
        ]);
    }

    public function storeMeeting(Request $req) {
		$curl = curl_init();
		$title = $req->title;
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://webexapis.com/v1/meetings',
		  CURLOPT_RETURNTRANSFER => false,
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
		  "sendEmail": true,
		  "title": "'.$title.'",
		  "start": "2021-11-30T20:30:00+08:00",
		  "end": "2021-11-30T21:00:00+08:00",
		  "timezone": "Asia/Manila"
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer MzFiZTE4MjUtZTdkYi00MWExLTg4ZWYtZWEzNTVmZTA5MmQ0OWYzOTc5YzktMzVk_P0A1_caaa8419-6ffd-4ddb-8fb5-d89f059408c4',
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		dd($response);
    }
}
