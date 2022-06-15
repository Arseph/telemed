<div class="modal fade" role="dialog" id="IssueAndConcern">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="box box-danger direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span class="issue_concern_code"></span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- Conversations are loaded here -->
                    <div id="issue_and_concern_body" style="padding: 10px;">
                    </div>
                </div>

                <div class="box-footer issue_footer">
                    <form action="" method="post" id="sendIssue">
                        {{ csrf_field() }}
                        <input type="hidden" id="issue_meeting_id" />
                        <div class="input-group">
                            <textarea id="issue_message" rows="3" required placeholder="Type a message for your issue and concern regarding this transaction.." class="form-control"></textarea>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success btn-lg">Send</button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- /.box-footer-->
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@if(count($pastmeetings)>0)
<div class="modal fade" role="dialog" id="teleconsultpastDetails">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="box box-info">
                <div class="box-header with-border">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="box-title">
                        Teleconsultation Details
                    </h3>
                </div>
                <div class="box-body">
                    <div class="pull-right">
                        @if($row->RequestTo == $active_user->id)
                        <a href="#docorder_modal" class="btn btn-warning btn-sm" data-toggle="modal" onclick="getDataDocOrder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{ $row->meetID }}', '{{$row->PatID}}')">
                            <i class="fas fa-user-md"></i> Doctor Order
                        </a>
                        <a href="#attachments_modal" class="btn btn-info btn-sm" data-toggle="modal" onclick="getattachment('@if($row->docorder){{$row->docorder->id}}@endif')">
                            <i class="fas fa-vials"></i> Lab Results/Attachments
                        </a>
                        @elseif($row->Creator == $active_user->id)
                        <button class="btn btn-info btn-sm"onclick="getDocorder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{$row->PatID}}')">
                            <i class="fas fa-file-medical"></i> Lab Request
                        </button>
                        <button class="btn btn-warning btn-sm"onclick="getPrescription('{{$row->meetID}}')">
                            <i class="fas fa-prescription"></i> Prescriptions
                        </button>
                        @endif
                    </div>
                <hr>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#demoINFO" onclick="telDetail('','demographic', 'patientTab')">Demographic Profile</a></li>
                        <li><a data-toggle="tab" href="#clinicINFO" onclick="telDetail('','clinical', 'clinicTab')">Clinical History and Physical Examination</a></li>
                        <li><a data-toggle="tab" href="#covidINFO" onclick="telDetail('','covid', 'covidTab')">Covid-19 Screening</a></li>
                        <li><a data-toggle="tab" href="#diagINFO" onclick="telDetail('','diagnosis', 'diagTab')">Diagnosis/Assessment</a></li>
                        <li><a data-toggle="tab" href="#planINFO" onclick="telDetail('','plan', 'planTab')">Plan of Management</a></li>
                      </ul>

                      <div class="tab-content">
                        <div id="demoINFO" class="tab-pane fade in active">
                          <h3><b id="caseNO"></b></h3>
                          <br>
                          <div class="disAble patientTab"></div>
                        </div>
                        <div id="clinicINFO" class="tab-pane fade">
                          <br>
                          <div class="disAble clinicTab"></div>
                        </div>
                        <div id="covidINFO" class="tab-pane fade">
                          <br>
                          <div class="disAble covidTab"></div>
                        </div>
                        <div id="diagINFO" class="tab-pane fade">
                          <br>
                          <div class="disAble diagTab"></div>
                        </div>
                        <div id="planINFO" class="tab-pane fade">
                          <br>
                          <div class="disAble planTab"></div>
                        </div>
                      </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif
<div class="modal fade" role="dialog" id="prescription_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="box-title">
                        <span>Prescriptions</span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="prescription_body" style="padding: 10px;">
                    </div>
                </div>

                <div class="box-footer issue_footer">
                    <button type="button" class="btn btn-success btn-lg"><i class="fas fa-print"></i> Print</button>
                </div>
                <!-- /.box-footer-->
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

