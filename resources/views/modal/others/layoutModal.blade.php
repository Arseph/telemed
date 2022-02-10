<?php 
$user = Session::get('auth');
$patients = \App\Patient::select(
        "patients.*",
        "bar.brg_name as barangay",
        "user.email as email",
        "user.username as username",
    ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
    ->leftJoin("users as user","user.id","=","patients.account_id")
    ->where('patients.doctor_id', $user->id)
    ->orderby('patients.lname','asc')
    ->paginate(10);
?>
<div class="modal fade" id="list_patient_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Patients</h4>
      </div>
      <div class="modal-body" id="meetingInfo">
  		<div class="form-group" style="width: 20%; margin-left: 10px;">
  			<input type="text" class="form-control searchPat" placeholder="Search patient...">
  		</div>
      	<div class="box-body">
            @if(count($patients)>0)
                <div class="table-responsive">
                    <table id="patientTable" class="table table-striped table-hover">
                    	<thead>
	                        <tr class="bg-black">
	                            <th>Name</th>
	                            <th>Gender</th>
	                            <th>Age / DOB</th>
	                            <th>Barangay</th>
	                            <th>Contact</th>
	                            <th>Username</th>
	                        </tr>
                    	</thead>
                        @foreach($patients as $row)
                        <tr onclick="gourl(<?php echo $row->id ?>)">
                            <td style="white-space: nowrap;">
                                <b class="title-info update_info">
                                    {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}
                                </b>
                            </td>
                            <td>{{ $row->sex }}</td>
                            <td>
                                @if($row->dob)
                                <b><?php echo
                                    \Carbon\Carbon::parse($row->dob)->format('F d, Y');
                                    ?></b><br>
                                <small class="text-success">
                                    <?php echo
                                    \Carbon\Carbon::parse($row->dob)->diff(\Carbon\Carbon::now())->format('%y years and %m months old');
                                    ?>
                                </small>
                                @endif
                            </td>
                            <td>{{ $row->barangay }}</td>
                            <td>{{ $row->contact }}</td>
                            <td>@if($row->account){{ $row->account->username }}@endif</td>
                        </tr>
                        @endforeach
                    </table>
                    
                </div>
            @else
                <div class="alert alert-warning">
                    <span class="text-warning">
                        <i class="fa fa-warning"></i> No Patients found!
                    </span>
                </div>
            @endif
        </div>
      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="feedbackModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Feedback</h4>
               </div>
            <div class="modal-body feedback_body">
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" role="dialog" id="issueModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Issues and Concern</h4>
               </div>
            <div class="modal-body issue_body">
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="sfeedbackModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Feedback</h4>
               </div>
            <div class="modal-body">
        <form action="{{ asset('superadmin/feedback/response') }}" method="POST">
        {{ csrf_field() }}
            <table class="table table-hover table-form table-striped">
         <input type="hidden" id="id" name="id" class="form-control">
         <input type="hidden" name="action" class="form-control" value="notified">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="prepared_by" class="form-control" value="" readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="subject" name="subject" class="form-control" readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Tel no.</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="tel_no" name="tel_no" class="form-control" readonly></td>
            </tr>
            <tr>
                <td class=""><label>Message</label></td>
                <td>:</td>
                <td><textarea class="form-control" id="message" name="message" rows="10" style="resize:none;" readonly></textarea></td>
            </tr>
            <tr>
                <td class=""><label>Remarks</label></td>
                <td>:</td>
                <td><textarea class="form-control" id="remarks" name="remarks" rows="10" style="resize:none;" required></textarea></td>
            </tr>
        </table>
        <div class="modal-footer">
        <!-- <a data-toggle="modal" class="btn btn-danger btn-sm btn-flat btn_subremove">
        <i class="fa fa-trash"></i> Remove
        </a> -->

        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        <button type="submit" class="btnSave btn btn-success"><i class="fas fa-check"></i> Save</button>
     </div>
     </form>
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
