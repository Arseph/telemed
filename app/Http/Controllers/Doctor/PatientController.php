<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facility;
use App\Barangay;
use App\Patient;
use App\User;
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
        ->where('user.doctor_id', $user->id)
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
        ->where('patients.doctor_id', $user->id)
        ->where('user.doctor_id', $user->id)->get();
        $users = User::where('doctor_id', $user->id)->get();
        return view('doctors.patient',[
            'data' => $data,
            'municity' => $municity,
            'patients' => $patients,
            'users' => $users
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
            'doctor_id' => $user->id,
            'facility_id' => $user->facility_id,
            'phic_status' => $req->phic_status,
            'phic_id' => $req->phic_id,
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'contact' => $req->contact,
            'dob' => $req->dob,
            'sex' => $req->sex,
            'civil_status' => $req->civil_status,
            'muncity' => $req->muncity,
            'province' => $province->p_id,
            'brgy' => $req->brgy,
            'address' => $req->address,
            'tsekap_patient' => 0
        );
        if($req->patient_id){
            Session::put("action_made","Successfully updated Patient");
            $patient = Patient::find($req->patient_id);
            $patient->update($data);
            if($req->email && $req->username && $req->password) {
                $data = array(
                    'doctor_id' => $user->id,
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
                    'doctor_id' => $user->id,
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
        $account->delete();
        $patient->delete();
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
}
