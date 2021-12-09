<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Facility;
use App\Province;
class ManageController extends Controller
{
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
}
