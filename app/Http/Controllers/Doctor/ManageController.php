<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Prescription;
use App\User;
use App\DrugsMeds;
class ManageController extends Controller
{
    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }

    public function prescription(Request $request)
    {
    	$user = Session::get('auth');
    	$lastid = Prescription::max('id');
    	$pres_code = $lastid ? "RX".$user->id.str_pad($lastid, 7, "0", STR_PAD_LEFT) : "RX".$user->id.str_pad(1, 7, "0", STR_PAD_LEFT);
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
        $data = Prescription::where(function($q) use ($keyword){
            $q->where('presc_code',"like","%$keyword%")
                ->orwhere('presc_code',"like","%$keyword%")
                ->orwhere('presc_code',"like","%$keyword%");
               
            })
            ->orderby('presc_code','asc')
            ->paginate(30);
        $drugmed = DrugsMeds::orderby('drugcode', 'asc')->get();
        $doctors = User::where('level', 'doctor')->orderby('lname', 'asc')->get();
        $prescription = Prescription::all();
        return view('doctors.prescription',[
            'data' => $data,
            'pres_code' => $pres_code,
            'drugmed' => $drugmed,
            'doctors' => $doctors,
            'prescription' => $prescription,
            'user' => $user
        ]);
    }

    public function prescriptionStore(Request $req) {
    	$user = Session::get('auth');
    	$req->request->add([
            'encodedby' => $user->id
        ]);
        if($req->id) {
        	$req->request->add([
	            'modifyby' => $user->id
	        ]);
            Prescription::find($req->id)->update($req->all());
            Session::put("action_made","Successfully Update Prescription.");
        } else {
            Prescription::create($req->all());
            Session::put("action_made","Successfully Add Prescription.");
        }
    }
}
