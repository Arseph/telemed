<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Issue;
use App\Facility;

class IssueConcernCtrl extends Controller
{
    public function index(Request $req)
    {
        $data = Issue::where('void',1)
        ->paginate(15);

        return view('doctors.issue',[
            'data' => $data
        ]);
    }

    public function IssueAndConcern($meet_id,$issue_from)
    {
        $facility = Facility::find($issue_from);
        $data = Issue::where("meet_id","=",$meet_id)->orderBy("id","asc")->get();

        return view('doctors.convo_issue',[
            'data' => $data,
            'facility' => $facility
        ]);
    }

    public function issueSubmit(Request $request)
    {
        $issue = $request->get('issue');
        $meeting_id = $request->get('meeting_id');
        $data  = array(
            "meet_id" => $meeting_id,
            "issue" => $issue,
            "status" => 'outgoing',
            "void" => 1
        );
        
        Issue::create($data);

        $facility = Facility::find(Session::get("auth")->facility_id);
        return view("doctors.issue_append",[
            "facility_name" => $facility->facilityname,
            "meeting_id" => $meeting_id,
            "issue" => $issue
        ]);
    }
}
