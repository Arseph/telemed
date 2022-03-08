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
    .modal {
      text-align: center;
      padding: 0!important;
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
    }

    .modal-dialog {
          width: 60%;
      display: inline-block;
      text-align: left;
      vertical-align: middle;
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
    /* width */
    ::-webkit-scrollbar {
      width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #52BCDC; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #337AB7; 
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
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
                                <b>{{ $row->title }}</b><br>
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
                              <div id="tabs{{$row->id}}" class="tab-pane fade @if($ctr1 == 0)in active @endif">
                                <h3>{{ $row->title }}</h3>
                                <label class="title-info update_info">Date:
                                      {{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}
                                    <br><b>
                                        <small class="text-warning">Time:
                                   {{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}
                                        </small>
                                    </b>
                                </label>
                                <p>Patient: {{ $row->patLname }}, {{ $row->patFname }} {{ $row->patMname }}</p>
                                @if($row->RequestTo == $active_user->id)
                                <b class="text-primary">Requested By: {{ $row->encoded->lname }}, {{ $row->encoded->fname }} {{ $row->encoded->mname }}</b>
                                <br>
                                <b>{{ $row->encoded->facility->facilityname }}</b>
                                <br>
                                <br>
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="startMeeting('<?php echo $row->meetID?>')" target="_blank">
                                    <i class="fas fa-play-circle"></i> Start Consultation
                                </a>
                                <a href="#docorder_modal" class="btn btn-warning" data-toggle="modal" onclick="getDataDocOrder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{ $row->meetID }}', '{{$row->PatID}}')">
                                    <i class="fas fa-user-md"></i> Doctor Order
                                </a>
                                @elseif($row->Creator == $active_user->id)
                                <b class="text-primary">Requested To: {{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b>
                                <br>
                                <b>{{ $row->doctor->facility->facilityname }}</b>
                                <br>
                                <br>
                                <a href="javascript:void(0)" class="btn btn-success" onclick="startMeeting('<?php echo $row->meetID?>')" target="_blank">
                                    <i class="fas fa-play-circle"></i> Join Consultation
                                </a>
                                <button class="btn btn-info"onclick="getDocorder('@if($row->docorder){{$row->docorder->id}}@endif', '{{$row->patFname}}', '{{$row->patMname}}', '{{$row->patLname}}', '{{$row->PatID}}')">
                                    <i class="fas fa-file-medical"></i> Lab Request
                                </button>
                                @endif
                                <br>
                                <br>
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
                                    <th>Teleconsult Date & Time</th>
                                    <th>Encoded By:</th>
                                    <th>Date Requested:</th>
                                    <th>Topic / Patient</th>
                                    <th>Status</th>
                                </tr>
                                @foreach($data_req as $row)
                                    <tr onclick="infoMeeting('<?php echo $row->meetID?>','<?php echo $row->meet_id?>')">
                                      <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                        <td style="width: 20%;">
                                            <a href="javascript:void(0)" class="title-info update_info">
                                               {{ \Carbon\Carbon::parse($row->time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->time)->addMinutes($row->duration)->format('h:i A') }}
                                                <br><b>
                                                    <small class="text-warning">
                                                        {{ \Carbon\Carbon::parse($row->datefrom)->format('l, F d, Y') }}
                                                    </small>
                                                </b>
                                            </a>
                                        </td>
                                        <td>
                                          <b class="text-primary">{{ $row->encoded->lname }}, {{ $row->encoded->fname }} {{ $row->encoded->mname }}</b><br>
                                          <b>{{ $row->encoded->facility->facilityname }}</b>
                                        </td>
                                        <td>
                                          <b class="text-warning"> {{ \Carbon\Carbon::parse($row->reqDate)->format('l, h:i A F d, Y') }}</b>
                                        </td>
                                        <td>
                                          <b >{{ $row->title }}</b>
                                          <br>
                                          <b class="text-muted">Patient: {{ $row->patLname }}, {{ $row->patFname }} {{ $row->patMname }}</b>
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
                                          <b>Patient: {{ $row->patLname }}, {{ $row->patFname }} {{ $row->patMname }}</b>
                                        </td>
                                        <td>
                                        <b>Patient: {{ $row->patLname }}, {{ $row->patFname }} {{ $row->patMname }}</b>
                                          <br>
                                          <a href="#IssueAndConcern" data-issue_from ='{{$row->encoded->facility->id}}' data-meet_id ='{{$row->meetID}}' data-toggle="modal" class="btn-circle btn-danger btn-issue-referred">
                                              <i class="fas fa-exclamation-triangle"></i> Issues & concern
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
            <div id='calendar'></div>
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
