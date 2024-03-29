<?php
    $user = Session::get('auth');
?>
<div class="modal fade" id="tele_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">Request Teleconsultation</h4>
      </div>
      <div class="modal-body" id="meetingInfo">
      	<form id="tele_form" method="POST">
      		{{ csrf_field() }}
    		<input type="hidden" name="meeting_id">
    		<input type="hidden" name="user_id" value="{{ Session::get('auth')->id }}">
        @if($active_user->level == 'doctor')
      	<div class="form-group" id="facilityField">
            <label>Facility:</label>
            <select id="reqFac" class="form-control select2 selectFacility" name="facility_id" required>
            	<option value="">Select Facility ...</option>
	              @foreach($facilities as $fac)
	                <option value="{{ $fac->id }}">{{ $fac->facilityname }}</option>
                 @endforeach 
            </select>
        </div>
        <div class="form-group hide" id="catField">
            <label>Doctor Category:</label>
            <select class="form-control select2 selectCatRequest" name="tele_cate_id" required>
              <option value="">Select Category ...</option>
                @foreach($telecat as $tel)
                  <option value="{{ $tel->id }}">{{ $tel->category_name }}</option>
                 @endforeach 
            </select>
        </div>
        @endif
	     <div id="scheduleMeeting" class="@if($active_user->level !='patient')hide @endif">
        @if($active_user->level !='patient')
    		<div class="form-group">
          <label>Doctor:</label>
          <select class="form-control select2 selectDoctor" name="doctor_id" required>
          </select>
        </div>
        <div class="form-group">
        	 <label>Patient:</label>
          <select class="form-control select2" name="patient_id" id="patient_id" required>
          	<option value="">Select Patient ...</option>
              @foreach($patients as $pat)
                <option value="{{ $pat->id }}">{{\Crypt::decrypt($pat->lname)}}, {{\Crypt::decrypt($pat->fname)}} {{\Crypt::decrypt($pat->mname) }}</option>
               @endforeach 
          </select>
        </div>
        <hr>
        @else
        <input type="hidden" name="patient_id" value="{{$active_user->patient->id}}">
        <input type="hidden" name="doctor_id" value="{{$active_user->patient->doctor_id}}">
        <input type="hidden" name="tele_cate_id" value="{{$active_user->patient->mydoctor->doc_cat_id}}">
        <input type="hidden" name="facility_id" value="{{$active_user->patient->facility_id}}">

        @endif
  	     	<div class="form-group">
  		     	<label>Chief Complaint:</label>
  		        <input type="text" class="form-control" value="" name="title" required>
  		    </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
		        <button type="submit" class="btnSavePend btn btn-success"><i class="fas fa-check"></i> Save</button>
		     </div>
	      </div>
  	</form>
      </div>
    </div>
  </div>
</div>
@if($user->level=='doctor')
<div class="modal fade" id="tele_request_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">Request Teleconsultation</h4>
      </div>
      <div class="modal-body" id="meetingInfo">
      	<form id="accept_decline_form" method="POST">
      		{{ csrf_field() }}
    		<input type="hidden" id="req_meeting_id">
      	<div class="row">
      		<div class="col-lg-8">
      			<label>Encoded by: <label class="text-muted" id="txtEncoded"></label></label><br>
      			<label id="req_fac"></label>
      		</div>
      		<div class="col-lg-4">
      			<label>Date Requested: <label class="text-muted" id="txtreqDate"></label></label>
      		</div>
      	</div>
      	<br>
	     <div id="scheduleMeeting">
        <div class="form-group">
        	 <label>Patient:</label>
	         <input type="text" class="form-control" value="" id="req_patient" readonly>
        </div>
	     	<div class="form-group">
		     	<label>Chief Complaint:</label>
		        <input type="text" class="form-control" value="" id="req_title" readonly>
		     </div>
		     
         <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Date of teleconsultation:</label>
              <input type="text" value="" name="date_from" class="form-control daterange" placeholder="Select Date" onchange="validateTIme()" required/>
            </div>
          </div>
           <div class="col-sm-3">
            <label>Duration:</label>
            <select class="form-control duration" name="duration" onchange="validateTIme()" required>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="30">45 Minutes</option>
                    <option value="60">60 Minutes</option>
                </select>
           </div>
           <div class="col-sm-3">
            <label>Time:</label>
              <div class="input-group clockpicker" data-placement="top" data-align="top">
                <input type="text" class="form-control" name="time" placeholder="Time" value="" required>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
           </div>
           <div class="col-sm-12">
             <a data-target="#calendar_meetings_modal" data-toggle="modal" id="showCalendar" 
       href="#calendar_meetings_modal">Show My Calendar</a>
           </div>
          </div>
		      <div class="modal-footer">
		        <a data-target="#decline_modal" data-toggle="modal" 
       href="#decline_modal" class="btn btn-danger"><i class="fas fa-times"></i>&nbsp;Decline</a>
		        <button id="acceptBtn" type="submit" class="btnSave btn btn-success" value="Accept"><i class="fas fa-check"></i> Accept</button>
		     </div>
	      </div>
  	</form>
      </div>
    </div>
  </div>
</div>
@endif
<div class="modal fade" id="info_meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myInfoLabel"></h3>
      </div>
      <div class="modal-body">
        <h4 id="ReqMyFac" class="text-primary"></h4>
        <h4 id="timeConsult" class="text-success"></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btnMeeting btn btn-primary"><i class="fas fa-play-circle"></i> Start Consultation</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myrequest_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">My Request</h4>
      </div>
      <div class="modal-body" id="myReqInfo">
      	@if(count($data_my_req)>0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tr class="bg-black">
                            <th></th>
                            <th>Requested To:</th>
                            <th>Date Requested:</th>
                            <th>Chief Complaint / Patient</th>
                            <th>Status</th>
                        </tr>
                        @foreach($data_my_req as $row)
                            <tr onclick="getMeeting('<?php echo $row->meet_id?>', 'yes')">
                              <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                <td>
                                  @if($row->doctor)
                                  <b class="text-primary">{{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b><br>
                                  <b>{{ $row->doctor->facility->facilityname }}</b>
                                  @else
                                  <b>{{ $row->facility->facilityname }}</b>
                                  @endif
                                  <b>{{ $row->facility->facilityname }}</b>
                                </td>
                                <td>
                                  <b class="text-warning"> {{ \Carbon\Carbon::parse($row->reqDate)->format('l, h:i A F d, Y') }}</b>
                                </td>
                                <td>
                                  <b >{{ $row->title }}</b>
                                  <br>
                                  <b class="text-muted">Patient: {{\Crypt::decrypt($row->patLname)}}, {{\Crypt::decrypt($row->patFname)}} {{\Crypt::decrypt($row->patMname) }}</b>
                                </td>
                                <td>
                                  @if($row->status == 'Accept')
                                  <span class="badge bg-green">Accepted</span>
                                  @elseif($row->status == 'Pending')
                                  <span class="badge badge-warning">Pending</span>
                                  @elseif($row->status == 'Declined')
                                  <span class="badge bg-red">Declined</span>
                                  @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pagination" id="pageMyReq">
                        {{ $data_my_req->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <span class="text-warning">
                        <i class="fa fa-warning"></i> No Teleconsultation found!
                    </span>
                </div>
            @endif
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Okay</button>
		     </div>
	      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="calendar_meetings_modal" role="dialog" aria-labelledby="calendar_meetings_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">My Calendar</h4>
      </div>
      <div class="modal-body" id="calendarInfo">
        <div id='fac-calendar'></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="tele_details_modal" role="dialog" aria-labelledby="calendar_meetings_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="TelDetailHead"></h4>
      </div>
      <div class="modal-body">
        <div id='tele_detail_body'></div>
      </div>
      <div class="modal-footer">
          <button id="cancelBtnDetails" type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
          <button id="saveBtnForm" type="button" class="btnSavePend btn btn-success"><i class="fas fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="decline_modal" role="dialog" aria-labelledby="calendar_meetings_modal" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
            <form id="decline_form" method="POST">
            {{ csrf_field() }}
              <div class="form-group">
                  <label>Reason for Declining:</label>
                  <textarea class="form-control" name="decline_message" rows="2" required></textarea>
              </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success welbtn">Okay</button>
          </form>
        </div>
    </div>
  </div>
</div>