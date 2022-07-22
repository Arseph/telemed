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

<div class="modal fade" id="doccatModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Welcome! Please finish your profile</h4>
        </div>
        <div class="modal-body">
            <form id="welcome_form" method="POST">
            {{ csrf_field() }}
              <div class="form-group">
                <label>Doctor Category:</label>
                <select class="form-control select2" name="tele_cate_id" required>
                    <?php
                    $telecat = \App\DocCategory::orderBy('category_name', 'asc')->get();
                    ?>
                  <option value="">Select your category ...</option>
                    @foreach($telecat as $tel)
                      <option value="{{ $tel->id }}">{{ $tel->category_name }}</option>
                     @endforeach 
                </select>
              </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success welbtn">Okay</button>
          </form>
        </div>
      </div>
    </div>
  </div>


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

<div class="modal fade" role="dialog" id="zoomCredentialModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Zoom Credentials</h4>
               </div>
            <div class="modal-body">
                <form id="zoomCreditForm" method="POST">
                {{ csrf_field() }}
                    <table class="table table-hover table-form table-striped">
                    <?php $facs = \App\Facility::orderBy('facilityname', 'asc')->get();?>
                    <tr>
                        <td class="col-sm-3"><label>Facility</label></td>
                        <td class="col-sm-1">:</td>
                        <td class="col-sm-8"><select class="form-control select2 selectFacility" name="facility_id" required>
                          <option value="">Select Facility ...</option>
                            @foreach($facs as $f)
                              <option value="{{ $f->id }}">{{ $f->facilityname }}</option>
                             @endforeach 
                        </select></td>
                    </tr>
                    <tr>
                        <td class="col-sm-3"><label>Doctor Category</label></td>
                        <td class="col-sm-1">:</td>
                        <td class="col-sm-8"><select class="form-control select2 selectCat" required>
                          <option value="">Select Category ...</option>
                            @foreach($telecat as $tel)
                              <option value="{{ $tel->id }}">{{ $tel->category_name }}</option>
                             @endforeach 
                        </select></td>
                    </tr>
                    <tr>
                        <td class="col-sm-3"><label>Doctor</label></td>
                        <td class="col-sm-1">:</td>
                        <td class="col-sm-8"><select id="fac_doc_id" class="form-control select2" name="doctor_id" required>
                          <option value="">Select Doctor ...</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td class="col-sm-3"><label>Zoom Client ID</label></td>
                        <td class="col-sm-1">:</td>
                        <td class="col-sm-8"><textarea class="form-control" name="zoom_client_id" rows="3" required></textarea></td>
                    </tr>
                    <tr>
                        <td class="col-sm-3"><label>Zoom Client Secret</label></td>
                        <td class="col-sm-1">:</td>
                        <td class="col-sm-8"><textarea class="form-control" name="zoom_client_secret" rows="3" required></textarea></td>
                    </tr>
                    <tr>
                        <td class=""><label>Zoom API Key</label></td>
                        <td>:</td>
                        <td><textarea class="form-control" name="zoom_api_key" rows="3" required></textarea></td>
                    </tr>
                    <tr>
                        <td class=""><label>Zoom API Secret</label></td>
                        <td>:</td>
                        <td><textarea class="form-control" name="zoom_api_secret" rows="3" required></textarea></td>
                    </tr>
                </table>
                <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
                <button type="submit" class="btnSave btn btn-success"><i class="fas fa-check"></i> Save</button>
             </div>
             </form>
            </div>
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
