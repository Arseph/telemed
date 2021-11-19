<div class="modal fade" id="patient_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Patient</h4>
      </div>
      <div class="modal-body">
      	<form id="patient_form" method="POST">
      		{{ csrf_field() }}
          <div class="text-right">
            <button id="deleteBtn" type="submit" class="btnSave btn btn-danger hide"><i class="fas fa-trash"></i> Delete</button>
          </div>
          <input type="hidden" class="form-control" value="" autofocus="" name="patient_id" id="patient_id">
          <div class="row">
		     <div class="col-sm-6">
		     	<label>PhilHealth Status:</label>
		            <select class="form-control select_phic" name="phic_status" required>
		            	<option value="None">None</option>
		                <option value="Member">Member</option>
		                <option value="Dependent">Dependent</option>
		            </select>
		     </div>
		     <div class="col-sm-6">
		     	<label>PhilHealth ID:</label>
		            <input type="text" class="form-control phicID" value="" name="phic_id" disabled>
		     </div>
		 </div>
		 <div class="row">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>First Name:</label>
		            <input type="text" class="form-control" value="" name="fname" required>
		        </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Middle Name:</label>
		            <input type="text" class="form-control" value="" name="mname">
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Last Name:</label>
		            <input type="text" class="form-control" value="" name="lname" required>
		        </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Contact Number:</label>
		            <input type="text" class="form-control" value="" name="contact" required>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Birth Date:</label>
		            <input type="date" class="form-control" value="" min="1910-05-11" max="{{ date('Y-m-d') }}" name="dob" required>
		        </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Sex:</label>
		            <select class="form-control sex" name="sex" required>
		                <option value="Male">Male</option>
		                <option value="Female">Female</option>
		            </select>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Civil Status:</label>
		            <select class="form-control civil_status" name="civil_status" required>
		                <option value="Single">Single</option>
		                <option value="Married">Married</option>
		                <option value="Divorced">Divorced</option>
		                <option value="Separated">Separated</option>
		            </select>
		        </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Municipality:</label>
		            <select class="form-control muncity filter_muncity select2" name="muncity" required>
		        		<option value="">Select Municipal/City...</option>
			              @foreach($municity as $m)
			                <option value="{{ $m->m_id }}">{{ $m->muncity }}</option>
			                 @endforeach 
			             <option value="others">Others</option>
	                </select>
		        </div>
		    </div>
		</div>
		<div class="form-group barangay_holder">
	        <label>Barangay:</label>
	        <select class="form-control barangay select2" name="brgy" required>
	            <option value="">Select Barangay...</option>
	        </select>
	    </div>
	    <div class="has-group others_holder hide">
	         <label>Complete Address :</label>
	        <input type="text" name="address" class="form-control others" placeholder="Enter complete address..." />
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

<div class="modal fade" id="create_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabelAccount">Create account for patient</h4>
      </div>
      <div class="modal-body">
      	<form id="create_form" method="POST">
      		{{ csrf_field() }}
      		<input type="hidden" class="form-control" value="" autofocus="" name="account_id" id="account_id">
          <div class="row">
		     <div class="col-sm-6">
		     	<label>First Name:</label>
		        <input type="text" class="form-control" value="" name="fname" readonly>
		     </div>
		     <div class="col-sm-6">
		     	 <label>Middle Name:</label>
		        <input type="text" class="form-control" value="" name="mname" readonly>
		     </div>
		 </div>
		 <div class="row">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Last Name:</label>
		        <input type="text" class="form-control" value="" name="lname" readonly>
		        </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Contact Number:</label>
			        <input type="text" class="form-control" value="" name="contact" readonly>
		        </div>
		    </div>
		</div>
		<hr>
		<div class="form-group">
            <label>Email Address:</label>
	        <input type="text" class="form-control" id="email" name="email" value="" required>
	        <div class="email-has-error text-bold text-danger hide">
	            <small>Email already taken!</small>
	        </div>
        </div>
        <div class="form-group">
            <label>Username:</label>
	        <input type="text" class="form-control" id="username" value="" name="username">
	        <div class="username-has-error text-bold text-danger hide">
	            <small>Username already taken!</small>
	        </div>
        </div>
        <button type="button" class="btn btn-warning btn-sm btn-flat generateUsername">
            <i class="fas fa-random"></i> Generate Username
        </button>
		<div class="row rowPass">
		    <div class="col-sm-12">
		        <div class="form-group">
		            <label>Password:</label>
		        <input type="text" class="form-control" value="" name="password">
		        </div>
	            <button type="button" class="btn btn-warning btn-sm btn-flat generatePassword">
                    <i class="fas fa-key"></i> Generate Password
                </button>
		    </div>
		    <br>
		</div>
      <div class="modal-footer rowFoot">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        <button type="submit" class="btnSaveAccount btn btn-success"><i class="fas fa-check"></i> Save</button>
  	</form>
      </div>
    </div>
  </div>
</div>