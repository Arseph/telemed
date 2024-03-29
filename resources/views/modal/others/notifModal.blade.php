<style>
  label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
</style>

<div class="modal fade" id="req_patient_modal" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Patient</h4>
    </div>
    <?php
      $user = Session::get('auth');
      $doctorslist = \App\User::where('facility_id', $user->facility_id)->where('level','doctor')->orderBy('lname', 'asc')->get();
    ?>
    <div class="modal-body">
      <div class="col-sm-12">
        <label id="notifComplaint"></label>
      </div>
      <div id="patientNotif"></div>
      @if($user->level == 'admin')
      <div class="row">
        <hr>
        <div class="col-sm-12">
            <label class="required-field">Select doctor to assign patient:</label>
            <select class="form-control select21" id="doctorIDnotif" required>
                <option value="" selected>Select Doctor...</option>
                @foreach($doctorslist as $doc)
                <option value="{{ $doc->id }}">{{ $doc->lname }}, {{ $doc->fname }} {{ $doc->mname }}</option>
                @endforeach
            </select>
          <br>
        </div>
      </div>
      @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        @if($user->level=='doctor')
        <button type="button" class="proceedNotifBtn btn btn-success">Proceed to Teleconsultation</button>
        @else
        <button type="submit" class="btnNotifAccept btn btn-success" disabled><i class="fas fa-check"></i> Accept</button>
        @endif
     </div>
    </div>
  </div>
</div>

<div class="modal fade" id="notif_tele_request_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalMeetingLabel">Request Teleconsultation</h4>
      </div>
      <div class="modal-body">
        <form id="notif_accept_decline_form" method="POST">
          {{ csrf_field() }}
        <input type="hidden" id="notif_meeting_id">
        <div class="row">
          <div class="col-lg-8">
            <label>Encoded by: <label class="text-muted" id="notiftxtEncoded"></label></label><br>
            <label id="notif_fac"></label>
          </div>
          <div class="col-lg-4">
            <label>Date Requested: <label class="text-muted" id="notifreqDate"></label></label>
          </div>
        </div>
        <br>
       <div id="notifscheduleMeeting">
        <div class="form-group">
           <label>Patient:</label>
           <input type="text" class="form-control" value="" id="notif_patient" readonly>
        </div>
        <div class="form-group">
          <label>Chief Complaint:</label>
            <input type="text" class="form-control" value="" id="notif_title" readonly>
         </div>
         
         <div class="row">
           <div class="col-sm-6">
            <label>Date of teleconsultation:</label>
            <input type="text" value="" name="date_from" class="form-control daterange" placeholder="Select Date" required/>
           </div>
           <div class="col-sm-3">
            <label>Duration:</label>
            <select class="form-control duration" name="duration" onchange="notifvalidateTIme()" required>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
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
             <a data-target="#notif_calendar_meetings_modal" data-toggle="modal" id="notifshowCalendar" 
       href="#notif_calendar_meetings_modal">Show My Calendar</a>
           </div>
          </div>
          <div class="modal-footer">
            <label class="countdowntoken"></label><i data-toggle="tooltip" title="Access token is use to generate zoom meeting informations like meeting link, meeting id, password etc." class="fa-solid fa-circle-question"></i>&nbsp;
            <a class="refTok"></a>
            <button type="submit" class="btnSave btn btn-danger" value="Declined"><i class="fas fa-times"></i>&nbsp;Decline</button>
            <button id="notifacceptBtn" type="submit" class="btnSave btn btn-success" value="Accept"><i class="fas fa-check"></i> Accept</button>
         </div>
        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="request_info_meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="reqmyInfoLabel"></h3>
      </div>
      <div class="modal-body">
        <h4 id="reqtimeConsult" class="text-success"></h4>
        <div class="form-group">
        <label>Patient:</label>
          <input type="text" id="reqpatientName"class="form-control" readonly>
       </div>
      <div class="form-group">
        <label class="text-success">Meeting Link:</label><br>
        <label id="reqmeetlink"></label>
        <a href="javascript:void(0)"onclick="reqcopyToClipboard('#reqmeetlink')"><i class="far fa-copy"></i></a>

      </div>
      <div class="form-group">
        <label class="text-success">Meeting Number:</label><br>
        <label id="reqmeetnumber"></label>

      </div>
      <div class="form-group">
        <label class="text-success">Password:</label><br>
        <label id="reqmeetPass"></label>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btnMeeting btn btn-primary"><i class="fas fa-play-circle"></i> Start Consultation</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="accept_notif_tele_request_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Create Teleconsultation</h4>
      </div>
      <div class="modal-body">
        <form id="accept_notif_accept_decline_form" method="POST">
          {{ csrf_field() }}
       <div id="acceptnotifscheduleMeeting">
        <div class="form-group">
          <label>Chief Complaint:</label>
            <input type="text" class="form-control" name="accept_notif_title" required>
         </div>
         
         <div class="row">
           <div class="col-sm-6">
            <label>Date of teleconsultation:</label>
            <input type="text" value="" name="accept_date_from" class="form-control daterange" placeholder="Select Date" required/>
           </div>
           <div class="col-sm-3">
            <label>Duration:</label>
            <select class="form-control duration" name="accept_duration" onchange="notifvalidateTIme()" required>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                </select>
           </div>
           <div class="col-sm-3">
            <label>Time:</label>
              <div class="input-group clockpicker" data-placement="top" data-align="top">
                <input type="text" class="form-control" name="accept_time" placeholder="Time" value="" required>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
           </div>
           <div class="col-sm-12">
             <a data-target="#notif_calendar_meetings_modal" data-toggle="modal"
       href="#notif_calendar_meetings_modal">Show Facility Calendar</a>
           </div>
          </div>
          <div class="modal-footer">
            <label class="countdowntoken"></label><i data-toggle="tooltip" title="Access token is use to generate zoom meeting informations like meeting link, meeting id, password etc. If token expired, Please contact your administrator" class="fa-solid fa-circle-question"></i>&nbsp;
            <a class="refTok"></a>
            <button id="accnotifacceptBtn" type="submit" class="btn btn-success" value="Accept"><i class="fas fa-check"></i> Save</button>
         </div>
        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="notif_calendar_meetings_modal" role="dialog" aria-labelledby="calendar_meetings_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Facility Calendar</h4>
      </div>
      <div class="modal-body">
        <div id='notif-fac-calendar'></div>
      </div>
    </div>
  </div>
</div>