@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .btn-circle {
      position: relative;
      display: inline-block;
      padding: 5%;
      border-radius: 30px;
      text-align: center;
      border-style: hidden;
    }
    .avatar {
      vertical-align: middle;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border-style: hidden;
    }
    .disAble {
        pointer-events:none;
    }
    @media only screen and (max-width: 4000px) {
      #infoTeleconsultMobile { display: none; }
    }
    @media only screen and (max-width: 760px) {
      #infoTeleconsultMobile { display: block; }
      .btn-disable
      {
        cursor: not-allowed;
        pointer-events: none;
        color: #c0c0c0;
        background-color: #ffffff;
      }
    }
    .disabledMeet {
      pointer-events: none;
      cursor: default;
    }
</style>
<div class="container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="pull-right">
                <a data-toggle="modal" class="btn btn-success btn-md" data-target="#tele_modal">
                    <i class="far fa-calendar-plus"></i> Request Teleconsult
                </a>
                <a data-toggle="modal" class="btn btn-info btn-md" data-target="#myrequest_modal">
                    <i class="far fa-calendar-plus"></i> My Request
                </a>
                <select class="btn btn-primary btn-md" id="sel1">
                  <option value="1">List</option>
                  <option value="0">Calendar</option>
                </select>
            </div>
            <h3>My Teleconsultations</h3>
        </div>
        <div class="box-body">
          <div id="teleList">
            <ul class="nav nav-pills">
              <li class="@if($active_tab == 'upcoming')active @endif"><a data-toggle="tab" href="#upcoming">Upcoming</a></li>
              @if($active_user->level == 'doctor')
              <li class="@if($active_tab == 'request')active @endif"><a data-toggle="tab" href="#request">Request @if($pending > 0)<span class="badge">{{$pending}}</span> @endif</a></li>
              @endif
              <li class="@if($active_tab == 'completed')active @endif"><a data-toggle="tab" href="#completed">Completed</a></li>
            </ul>
            <div class="pull-right">
              <a class="btnBack btn hide" data-toggle="tab">
                  <i class="fas fa-chevron-circle-left"></i> Back to consultation
              </a>
            </div>

            <div class="tab-content">
              <div id="upcoming" class="tab-pane fade in @if($active_tab == 'upcoming')active @endif">
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/teleconsultation') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group-md" style="margin-bottom: 10px;">
                                <input type="hidden" name="active_tab" value="upcoming">
                                <input type="text" class="form-control" name="date_range" value="{{$search}}"placeholder="Filter your date here..." id="consolidate_date_range" readonly>
                                <button type="submit" class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                <button type="submit" value="view_all" name="view_all" class="btn btn-warning btn-sm btn-flat">
                                    <i class="fa fa-eye"></i> View All
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 box-body">
                    @if(count($data)>0)
                        <div class="row">
                          <div class="col-md-4">
                            <ul class="nav nav-pills nav-stacked" style="overflow: auto; height: 350px;">
                              <?php $ctr = 0;  ?>
                              @foreach($data as $row)
                              <?php
                              $join = '';
                              if($row->RequestTo == $active_user->id) {
                                $join = 'no';
                              } else if($row->Creator == $active_user->id) {
                                $join = 'yes';
                              }
                              ?>
                              <li class="@if($ctr == 0)active @endif"><a data-toggle="tab" href="#tabs{{$row->id}}">
                                <b style="text-transform: uppercase;" class="title-info">{{ $row->title }}</b><br>
                                <label class="text-warning update_info">{{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}
                                    <br><b>
                                        <small class="text-warning">
                                          {{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}
                                        </small>
                                    </b>
                                </label>
                              </a></li>
                              <?php $ctr++; ?>
                              @endforeach
                            </ul>
                          </div>
                          <div class="col-md-8">
                            <div class="tab-content">
                              <?php $ctr1 = 0;  ?>
                              @foreach($data as $row)
                              <div id="tabs{{$row->id}}" class="tab-pane fade in @if($ctr1 == 0)active @endif">
                                <h3 class="title-info" style="text-transform: uppercase; font-size: 150%;">{{ $row->title }}</h3>
                                <h4 class="patientName{{$row->id}} text-green update_info">Patient: {{ \Crypt::decrypt($row->patLname) }}, {{ \Crypt::decrypt($row->patFname) }} {{ \Crypt::decrypt($row->patMname) }}<i class="fas fa-info-circle" data-toggle="collapse" data-target="#morepatInfo{{$row->id}}"></i></h4>
                                <div id="morepatInfo{{$row->id}}" class="collapse">
                                  <div style="margin-bottom: 6px;">{{\Carbon\Carbon::parse($row->dob)->diff(\Carbon\Carbon::now())->format('%y years %m months old and %d day(s)')}}</div>
                                  <div>Birthdate: <label>{{\Carbon\Carbon::parse($row->dob)->format('F d, Y')}}</label></div>
                                  <div>Sex: <label>{{$row->sex}}</label></div>
                                  <div>Civil Status: <label>{{$row->civil_status}}</label></div>
                                  <hr>
                                </div>
                                <label>Date:
                                      {{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}
                                    <br><b>
                                        <small class="text-warning">Time:
                                   {{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}
                                        </small>
                                    </b>
                                </label>
                                @if($row->pendmeet)<p>Type of Consultation:
                                  {{$row->pendmeet->telecategory->category_name}} @endif</p>
                                @if($row->RequestTo == $active_user->id)
                                @if($row->pendmeet)<b class="text-primary">Requested By: {{ $row->encoded->lname }}, {{ $row->encoded->fname }} {{ $row->encoded->mname }} {{$row->status}}</b>
                                <br>@endif
                                @if($row->encoded->level!='patient')
                                <b>{{ $row->encoded->facility->facilityname }}</b>
                                <br>
                                @endif
                                <br>
                                <?php
                                $id = \Crypt::encrypt($row->meetID);
                                $dis = '';
                                $date = \Carbon\Carbon::now();
                                $sdate = \Carbon\Carbon::parse($row->from_time);
                                $edate = \Carbon\Carbon::parse($row->to_time);
                                if (!$date->between($sdate, $edate)) {
                                    $dis='disabled';
                                } else {
                                    $dis='';
                                }
                                ?>
                                <a href="{{ asset('/start-meeting') }}/{{$id}}" class="btn btn-primary {{$dis}}" target="_blank" {{$dis}}>
                                    <i class="fas fa-play-circle"></i> Start Consultation
                                </a>
                                @elseif($row->Creator == $active_user->id)
                                <?php
                                $id = \Crypt::encrypt($row->meetID);
                                $dis = '';
                                $date = \Carbon\Carbon::now();
                                $sdate = \Carbon\Carbon::parse($row->from_time);
                                $edate = \Carbon\Carbon::parse($row->to_time);
                                if (!$date->between($sdate, $edate)) {
                                    $dis='disabled';
                                } else {
                                    $dis='';
                                }
                                ?>
                                <b class="text-primary">Requested To: {{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b>
                                <br>
                                <b>{{ $row->doctor->facility->facilityname }}</b>
                                <br>
                                <br>
                                <a href="{{ asset('/join-meeting') }}/{{$id}}" class="btn btn-success btn-disable {{$dis}}" target="_blank" {{$dis}}>
                                    <i class="fas fa-play-circle"></i> Join Consultation
                                </a>
                                @endif
                                <a class="btn btn-info" data-toggle="tab" href="#tabsTelDet{{$row->meetID}}" onclick="telDetail('<?php echo $row->meetID; ?>', 'demographic','patientTab','<?php echo $row->docorder ? $row->docorder->id : ""; ?>', '{{$row}}', '#tabs{{$row->id}}')">
                                    <i class="fa-solid fa-circle-info"></i> More Details
                                </a>
                                <br>
                                <br>
                                @if($active_user->level != 'patient')
                                <p>Teleconsult link:</p>
                                <label id="meetlinkZ">{{$row->web_link}}</label>
                                <a href="javascript:void(0)"onclick="copyToClipboard('#meetlinkZ')"><i class="far fa-copy"></i></a>
                                <br>
                                <br>
                                <p>Teleconsult ID:</p>
                                <label>{{$row->meeting_id}}</label>
                                <br>
                                <br>
                                <p>Password:</p>
                                <label>{{$row->password}}</label>
                                @endif
                              </div>
                              <div id="tabsTelDet{{$row->meetID}}" class="tab-pane fade in">
                                  <div class="pull-right">
                                    @if($row->RequestTo == $active_user->id)
                                    <a href="#docorder_modal" class="btn btn-warning btn-sm" data-toggle="modal" onclick="getDataDocOrder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{ $row->meetID }}', '{{$row->PatID}}')">
                                        <i class="fas fa-user-md"></i> Doctor Order
                                    </a>
                                    <a href="#attachments_modal" class="btn btn-info btn-sm" data-toggle="modal" onclick="getattachment('@if($row->docorder){{$row->docorder->id}}@endif')">
                                        <i class="fas fa-vials"></i> Lab Results/Attachments
                                    </a>
                                    @elseif($row->Creator == $active_user->id || $row->PatID == $active_user->patient->id)
                                    <button class="btn btn-info btn-sm"onclick="getDocorder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{$row->PatID}}')">
                                        <i class="fas fa-file-medical"></i> Lab Request
                                    </button>
                                    <button class="btn btn-warning btn-sm"onclick="getPrescription('{{$row->meetID}}')">
                                        <i class="fas fa-prescription"></i> Prescriptions
                                    </button>
                                    @endif
                                  </div>
                                  <h3>Teleconsultation Details</h3>
                                  <div>
                                    <h5 id="chiefCom{{$row->meetID}}" class="title-info update_info"></h5>
                                    <h5 class="text-green update_info">Patient: {{ \Crypt::decrypt($row->patLname) }}, {{ \Crypt::decrypt($row->patFname) }} {{ \Crypt::decrypt($row->patMname) }}</h5>
                                    <b><small id="chiefDate{{$row->meetID}}"></small></b>
                                    <br><b>
                                        <small id="chiefTime{{$row->meetID}}"></small>
                                    </b>
                                    <p id="chiefType{{$row->meetID}}"></p>
                                    <br>
                                  </div>
                                  <div class="text-center">
                                    <button class="btn btn-default btn-md" onclick="telDetail('','demographic', 'Demographic Profile')"><i class="far fa-address-card"></i> Demographic Profile</button>
                                    <button class="btn btn-default btn-md" onclick="telDetail('','clinical', 'Clinical History and Physical Examination')"><i class="fas fa-book-medical"></i> Clinical History and Physical Examination</button>
                                    <button class="btn btn-default btn-md" onclick="telDetail('','covid', 'Covid-19 Screening')"><i class="fas fa-virus"></i> Covid-19 Screening</button>
                                    <button class="btn btn-default btn-md" onclick="telDetail('','diagnosis', 'Diagnosis Assessment')"><i class="fas fa-diagnoses"></i> Diagnosis/Assessment</button>
                                  </div>
                              </div>
                              <?php $ctr1++; ?>
                              @endforeach
                            </div>
                          </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <span class="text-warning">
                                <i class="fa fa-warning"></i> No Teleconsultation found!
                            </span>
                        </div>
                    @endif
                </div>
                </div>
              </div>
              @if($active_user->level == 'doctor')
              <div id="request" class="tab-pane fade in @if($active_tab == 'request')active @endif">
                <h3>Request</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/teleconsultation') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group-md" style="margin-bottom: 10px;">
                                <input type="hidden" name="active_tab" value="request">
                                <input type="text" class="form-control" name="date_range_req" value="{{$search_req}}"placeholder="Filter your date here..." id="consolidate_date_range_req" readonly>
                                <select class="form-control" name="status_req">
                                    <option value="" selected>Select Status</option>
                                    <option value="Pending" @if($status_req == 'Pending')selected @endif>Pending</option>
                                    <option value="Accept" @if($status_req == 'Accept')selected @endif>Accepted</option>
                                    <option value="Declined" @if($status_req == 'Declined')selected @endif>Declined</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                <button type="submit" value="view_all" name="view_all_req" class="btn btn-warning btn-sm btn-flat">
                                    <i class="fa fa-eye"></i> View All
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 box-body">
                    @if(count($data_req)>0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr class="bg-black">
                                    <th></th>
                                    <th>Date Requested:</th>
                                    <th>Encoded By:</th>
                                    <th>Chief Complaint / Patient</th>
                                    <th>Status</th>
                                </tr>
                                @foreach($data_req as $row)
                                    <tr onclick="infoMeeting('<?php echo $row->meetID?>','<?php echo $row->meet_id?>')">
                                      <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                        <td>
                                          <b class="text-warning"> {{ \Carbon\Carbon::parse($row->reqDate)->format('l, h:i A F d, Y') }}</b>
                                        </td>
                                        <td>
                                          <b class="text-primary">@if($row->encoded->level=='patient')Patient: @endif{{ $row->encoded->lname }}, {{ $row->encoded->fname }} {{ $row->encoded->mname }}</b><br>
                                          <b>@if($row->encoded->level!='patient'){{ $row->encoded->facility->facilityname }} @endif</b>
                                        </td>
                                        <td>
                                          <b >{{ $row->title }}</b>
                                          <br>
                                          <b class="text-muted">Patient: {{\Crypt::decrypt($row->patLname)}}, {{\Crypt::decrypt($row->patFname)}} {{\Crypt::decrypt($row->patMname)}}</b>
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
                            <div class="pagination">
                                {{ $data->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <span class="text-warning">
                                <i class="fa fa-warning"></i> No Teleconsultation found!
                            </span>
                        </div>
                    @endif
                </div>
                </div>
              </div>
              @endif
              <!-- COmpleted Meetings -->
              <div id="completed" class="tab-pane fade in @if($active_tab == 'completed')active @endif">
                <h3>Completed</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/teleconsultation') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group-md" style="margin-bottom: 10px;">
                                <input type="hidden" name="active_tab" value="completed">
                                <input type="text" class="form-control" name="date_range_past" value="{{$search_past}}"placeholder="Filter your date here..." id="consolidate_date_range_past" readonly>
                                <button type="submit" class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                <button type="submit" value="view_all" name="view_all_past" class="btn btn-warning btn-sm btn-flat">
                                    <i class="fa fa-eye"></i> View All
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 box-body">
                    @if(count($pastmeetings)>0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                @foreach($pastmeetings as $row)
                                    <tr>
                                      <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                        <td style="width: 20%;">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-id= "{{ $row->id }}"
                                               class="title-info update_info"
                                               >
                                               {{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}
                                                <br><b>
                                                    <small class="text-warning">
                                                        {{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}
                                                    </small>
                                                </b>
                                            </a>
                                        </td>
                                        <td>
                                          <b class="text-primary">{{ $row->title }}</b>
                                          <br>
                                          <b>Patient: {{ \Crypt::decrypt($row->patLname) }}, {{ \Crypt::decrypt($row->patFname) }} {{ \Crypt::decrypt($row->patMname) }}</b>
                                        </td>
                                        <td>
                                        <b>Patient: {{ $row->patLname }}, {{ $row->patFname }} {{ $row->patMname }}</b>
                                          <br>
                                          <a href="#IssueAndConcern" data-issue_from ='{{$row->encoded->facility->id}}' data-meet_id ='{{$row->meetID}}' data-toggle="modal" class="btn btn-danger btn-issue-referred">
                                              <i class="fas fa-exclamation-triangle"></i> Issues & concern
                                          </a>
                                          <a href="#teleconsultpastDetails" data-toggle="modal" class="btn btn-info" onclick="telDetail('<?php echo $row->meetID; ?>', 'demographic','patientTab','<?php echo $row->docorder ? $row->docorder->id : ""; ?>', '{{$row}}', '')">
                                              <i class="fa-solid fa-circle-info"></i> Details
                                          </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="pagination">
                                {{ $data->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <span class="text-warning">
                                <i class="fa fa-warning"></i> No Meetings found!
                            </span>
                        </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="teleCalendar">
            <div id='my-calendar'></div>
          </div>
        </div>
</div>
@include('modal.doctors.issueModal')
@include('modal.teleconsult.teleconsultModal')
@include('modal.doctors.docordermodal')
@endsection
@section('js')
    @include('teleconsult.script.teleconsult')
    @include('teleconsult.script.calendar')
@endsection

