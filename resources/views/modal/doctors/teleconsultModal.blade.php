<div class="modal fade" id="meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
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
	        <input type="text" class="form-control" value="" name="title" required="">
	     </div>
	     <div class="row">
		     <div class="col-sm-6">
		     	<label>Date:</label>
		     	<input type="text" id="daterange" value="" name="datefrom" class="form-control" placeholder="Select Date" required />
		     </div>
		     <div class="col-sm-3">
		     	<label>Time:</label>
		     	<div class="input-group clockpicker">
				    <input type="text" class="form-control" name="timefrom" placeholder="Time" required>
				    <span class="input-group-addon">
				        <span class="glyphicon glyphicon-time"></span>
				    </span>
				</div>
		     </div>
		     <div class="col-sm-3">
		     	<label>Duration:</label>
		     	<select class="form-control duration" name="duration" required>
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
		        		<option value="">Select Patient Email...</option>
			              @foreach($patients as $p)
			                <option value="{{ $p->id }}">{{ $p->email }}</option>
		                 @endforeach 
	                </select>
		        </div>
		     </div>
		     <div class="col-sm-6">
		     	<label>Reminder Time:</label>
	            <input type="number" class="form-control phicID" value="" name="reminder_time">
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