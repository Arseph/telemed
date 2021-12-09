<div class="modal fade" id="meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Schedule Meeting</h4>
      </div>
      <div class="modal-body">
      	<form id="meeting_form" method="GET">
      		{{ csrf_field() }}
	     <div class="form-group">
	     	<label>Title:</label>
	        <input type="text" class="form-control" value="" name="title" required>
	     </div>
	     <div class="row">
		     <div class="col-sm-6">
		     	<label>Date:</label>
		     	<input type="text" id="daterange" value="" name="datefrom" class="form-control" placeholder="Select Date" onkeyup="validateTIme()" required/>
		     </div>
		     <div class="col-sm-3">
		     	<label>Time:</label>
		     	<div class="input-group clockpicker">
				    <input type="text" class="form-control" name="time" placeholder="Time" required>
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
		            <label>Patient:</label>
		            <select class="form-control muncity filter_muncity select2" name="email" required>
		        		<option value="">Select Patient ...</option>
			              @foreach($patients as $p)
			                <option value="{{ $p->id }}|{{$p->email}}">{{ $p->lname }}, {{ $p->fname }} {{ $p->mname }}</option>
		                 @endforeach 
	                </select>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        <button type="submit" class="btnSave btn btn-success"><i class="fas fa-check"></i> Save</button>
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