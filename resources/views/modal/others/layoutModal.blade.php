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
<div class="modal fade" id="webex_modal" role="dialog" aria-labelledby="webex_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <form id="webex_form" method="POST">
        {{ csrf_field() }}
        <small>Get your personal access webex token </small><a href="https://developer.webex.com/docs/getting-started" target="_blank">here</a><br>
        <div class="form-group">
            <label>Your Personal Access Token:</label>
            <input type="password" class="form-control" value="" name="webextoken" placeholder="Paste here..." required>
        </div>
        <small style="color: red;">Note: Please change your webex token every 12 hours.</small>
      <div class="modal-footer">
        <button type="submit" class="btnSaveWebex btn btn-success"><i class="fas fa-check"></i> Save</button>
    </form>
      </div>
    </div>
  </div>
</div>
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

