<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\PendingMeeting;
use App\Patient;
class HomeController extends Controller
{
	public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    } 

    public function index()
    {
        return view('doctors.home');
    }

    public function fetchNotif() {
        $user = Session::get('auth');
        $reqmeet = PendingMeeting::select(
        "pending_meetings.*",
        "pending_meetings.id as pendID",
        "pat.lname as patLname",
        "pat.fname as patFname",
        "pat.mname as patMname",
        "pat.id as PatID",
        "use.lname as fromLname",
        "use.fname as fromFname",
        "use.mname as fromMname",
        "fac.facilityname as facname",
        "pending_meetings.created_at as datereq",
        )->leftJoin("patients as pat", "pending_meetings.patient_id", "=", "pat.id")
        ->leftJoin("users as use", "pending_meetings.user_id", "=", "use.id")
        ->leftJoin("facilities as fac", "fac.id", "=", "use.facility_id")
        ->where('pending_meetings.doctor_id', $user->id)
        ->where('pending_meetings.status', 'Pending')
        ->orderBy('id','desc')
        ->get();
        $reqpatient = Patient::where('doctor_id', $user->id)
        ->where('is_accepted', 0)->get();
        $totalmeet = count($reqmeet);
        $totalpat = count($reqpatient);
        $totalreq = $totalmeet + $totalpat;
        return response()->json([
            'reqmeet'=>$reqmeet,
            'reqpatient'=>$reqpatient,
            'totalmeet'=>$totalmeet,
            'totalpat'=>$totalpat,
            'totalreq'=>$totalreq > 0 ? $totalreq : ''
        ]);
    }
}
