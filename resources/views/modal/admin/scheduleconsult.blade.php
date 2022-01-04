<div class="modal fade" id="meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">Schedule Meeting</h4>
      </div>
      <div class="modal-body" id="meetingInfo">
      	<form id="schedule_form" method="GET">
      		{{ csrf_field() }}
      	<input type="hidden" class="form-control" value="" autofocus="" name="meeting_id">
      	<input type="hidden" class="form-control" value="" autofocus="" name="patient_id">
      	<input type="hidden" class="form-control" value="{{ $user->id }}" autofocus="" name="user_id" id="user_id">
      	<div class="form-group">
            <label>Doctor:</label>
            <select class="form-control select2 selectDoctor" name="doctor_id" required>
        		<option value="">Select Doctor ...</option>
	              @foreach($users as $doctor)
	                <option value="{{ $doctor->id }}">{{ $doctor->lname }}, {{ $doctor->fname }} {{ $doctor->mname }} @if($doctor->email)(<small>{{$doctor->email}}</small>)@endif</option>
                 @endforeach 
            </select>
        </div>
	     <div id="scheduleMeeting" class="hide">
	     	<div class="form-group">
		     	<label>Title:</label>
		        <input type="text" class="form-control" value="" name="title" required>
		     </div>
		     <div class="row">
			     <div class="col-sm-6">
			     	<label>Date:</label>
			     	<input type="text" value="" name="datefrom" class="form-control daterange" placeholder="Select Date" required/>
			     </div>
			     <div class="col-sm-3">
			     	<label>Time:</label>
			     	<div class="input-group clockpicker">
					    <input type="text" class="form-control" name="time" placeholder="Time" value="" required>
					    <span class="input-group-addon">
					        <span class="glyphicon glyphicon-time"></span>
					    </span>
					</div>
			     </div>
			     <div class="col-sm-3">
			     	<label>Duration:</label>
			     	<select class="form-control duration" name="duration" onchange="validateTIme()" required>
		                <option value="10">10 Minutes</option>
		                <option value="20">20 Minutes</option>
		                <option value="30">30 Minutes</option>
		                <option value="40">40 Minutes</option>
		                <option value="50">50 Minutes</option>
		            </select>
			     </div>
			 </div>
			 <div class="row">
			     <div class="col-sm-6">
			     	<div class="form-group">
			            <label>Email:</label>
				        <input type="text" class="form-control" value="" name="email" readonly>
			        </div>
			     </div>
			     <div class="col-sm-6">
			     	<div class="form-group">
		     		  <br>
		              <label>Send Email to patient:</label><br>
		                <label><input type="radio" name="sendemail" value="true"  checked required>Yes</label>
		                <label><input type="radio" name="sendemail" value="false" required/>No</label>
			        </div>
			     </div>
			 </div>
		      <div class="modal-footer">
		        <button id="cancelBtn" type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
		        <button id="saveBtn" type="submit" class="btnSave btn btn-success"><i class="fas fa-check"></i> Save</button>
		     </div>
	      </div>
  	</form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="info_meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myInfoLabel"></h3>
      </div>
      <div class="modal-body">
      	<div class="form-group">
	     	<label>Patient:</label>
	        <input type="text" id="patientName"class="form-control" readonly>
	     </div>
  		<div class="form-group">
  			<label class="text-success">Meeting Link:</label><br>
  			<label id="meetlink"></label>
  			<a href="#"onclick="copyToClipboard('#meetlink')"><i class="far fa-copy"></i></a>

  		</div>
  		<div class="form-group">
  			<label class="text-success">Meeting Number:</label><br>
  			<label id="meetnumber"></label>

  		</div>
  		<div class="form-group">
  			<label class="text-success">Password:</label><br>
  			<label id="meetPass"></label>
  		</div>
  		<div class="form-group">
  			<label class="text-success">Host Key:</label><br>
  			<label id="meetKey"></label>
  		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btnMeeting btn btn-primary"><i class="fas fa-play-circle"></i> Start Meeting</button>
      </div>
    </div>
  </div>
</div>