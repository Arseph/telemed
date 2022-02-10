<div class="modal fade" id="docorder_modal" role="dialog" aria-labelledby="prescription_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Doctor Order</h4>
      </div>
      <div class="modal-body">
      	<form id="docorder_form" method="POST">
      		{{ csrf_field() }}
          <div class="text-right">
            <button id="deleteBtn" type="submit" class="btnSave btn btn-danger hide"><i class="fas fa-trash"></i> Delete</button>
          </div>
          <input type="hidden" class="form-control" value="" autofocus="" name="doctororder_id" id="doctororder_id">
          <input type="hidden" class="form-control" value="" autofocus="" name="doctororder_meet_id" id="doctororder_meet_id">
          <input type="hidden" class="form-control" value="" autofocus="" name="patientid" id="patientid">
          <div class="form-group">
            <label>Patient:</label>
            <input type="text" class="form-control" id="patient_name" disabled>
          </div>
          <div class="form-group">
            <label>Lab Request Codes:</label>
            <select multiple class="select2" id="labrequestcodes" required>
              <option value="BC">Blood Chemistry</option>
              <option value="CC">Clinical Chemistry</option>
              <option value="CBC">Complete Blood Count</option>
              <option value="F">Fecalysis</option>
              <option value="H">Hematology</option>
              <option value="I">Immunology</option>
              <option value="S">Serology</option>
              <option value="SM">Sputum Microscopy</option>
              <option value="U">Urinalysis</option>
                @foreach($labreq as $row)
                    <option value="{{ $row->req_code }}">{{ $row->description }}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Imaging Request Codes:</label>
            <select multiple class="select2" id="imagingrequestcodes" required>
              <option value="ECG">ECG</option>
              <option value="MRI">MRI</option>
              <option value="US">Ultrasound</option>
              <option value="XR">X-ray</option>
                @foreach($imaging as $row)
                    <option value="{{ $row->req_code }}">{{ $row->description }}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
              <label>Alert Description:</label>
              <textarea class="form-control" name="alertdescription" rows="2"></textarea>
          </div>
          <div class="form-group">
              <label>Treatment Plan:</label>
              <textarea class="form-control" name="treatmentplan" rows="2"></textarea>
          </div>
          <div class="form-group">
              <label>Remarks:</label>
              <textarea class="form-control" name="remarks" rows="3"></textarea>
          </div>
      		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Save</button>
  	</form>
      </div>
    </div>
  </div>
</div>