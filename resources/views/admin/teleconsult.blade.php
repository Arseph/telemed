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
      padding: 2%;
      border-radius: 30px;
      text-align: center;
    }
    .avatar {
      vertical-align: middle;
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
</style>
<div class="container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
          <div class="pull-right">
                <a data-toggle="modal" class="btn btn-success btn-md" data-target="#tele_modal">
                    <i class="far fa-calendar-plus"></i> Schedule Teleconsult
                </a>
            </div>
            <h3>Teleconsultations</h3>
        </div>
        <div class="box-body">
            <ul class="nav nav-pills">
              <li class="@if($active_tab == 'upcoming')active @endif"><a data-toggle="tab" href="#upcoming">Upcoming</a></li>
              <li class="@if($active_tab == 'request')active @endif"><a data-toggle="tab" href="#request">Request</a></li>
              <li class="@if($active_tab == 'completed')active @endif"><a data-toggle="tab" href="#completed">Completed</a></li>
            </ul>

            <div class="tab-content">
              <div id="request" class="tab-pane fade in @if($active_tab == 'request')active @endif">
                <h3>Request</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/admin-teleconsult') }}" method="POST" class="form-inline">
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
                                @foreach($data_req as $row)
                                    <tr>
                                      <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                        <td style="width: 20%;">
                                            <a href="#" class="title-info update_info">
                                               {{ \Carbon\Carbon::parse($row->time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->time)->addMinutes($row->duration)->format('h:i A') }}
                                                <br><b>
                                                    <small class="text-warning">
                                                        {{ \Carbon\Carbon::parse($row->datefrom)->format('l, F d, Y') }}
                                                    </small>
                                                </b>
                                            </a>
                                        </td>
                                        <td>
                                          <b class="text-primary">Doctor: {{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b><br>
                                          <b>{{ $row->doctor->facility->facilityname }}</b>
                                        </td>
                                        <td>
                                          <b >{{ $row->title }}</b>
                                          <br>
                                          <b class="text-muted">Patient: {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</b>
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
              <div id="upcoming" class="tab-pane fade in @if($active_tab == 'upcoming')active @endif"">
                <h3>Upcoming</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/admin-teleconsult') }}" method="POST" class="form-inline">
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                @foreach($data as $row)
                                    <tr onclick="getMeeting(<?php echo $row->meetID ?>)">
                                      <td style="width: 1%;"><button class="avatar btn-info"><i class="fas fa-calendar-day"></i></button></td>
                                        <td style="width: 20%;">
                                            <a href="#" class="title-info update_info">
                                               {{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}
                                                <br><b>
                                                    <small class="text-warning">
                                                        {{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}
                                                    </small>
                                                </b>
                                            </a>
                                        </td>
                                        <td>
                                          <b class="text-primary">Doctor: {{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b><br>
                                          <b>{{ $row->doctor->facility->facilityname }}</b>
                                        </td>
                                        <td>
                                          <b >{{ $row->title }}</b>
                                          <br>
                                          <b>Patient: {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</b>
                                        </td>
                                        <td>
                                          <a href="#" class="btn-circle btn-primary" onclick="startMeeting('<?php echo $row->meetID?>')" target="_blank">
                                              <i class="fas fa-play-circle"></i> Join Consultation
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
                                <i class="fa fa-warning"></i> No Teleconsultation found!
                            </span>
                        </div>
                    @endif
                </div>
                </div>
              </div>
              <!-- COmpleted Meetings -->
              <div id="completed" class="tab-pane fade in @if($active_tab == 'completed')active @endif"">
                <h3>Completed</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('/admin-teleconsult') }}" method="POST" class="form-inline">
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
                                            <a href="#"
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
                                          <b class="text-primary">Doctor: {{ $row->doctor->lname }}, {{ $row->doctor->fname }} {{ $row->doctor->mname }}</b><br>
                                          <b>{{ $row->doctor->facility->facilityname }}</b>
                                        </td>
                                        <td>
                                          <b class="text-primary">{{ $row->title }}</b>
                                          <br>
                                          <b>Patient: {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</b>
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
    
</div>
<!-- <div class="modal fade" id="info_meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myInfoLabel"></h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <label>Doctor:</label>
          <input type="text" id="docName"class="form-control" readonly>
       </div>
       <hr>
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
        <button type="button" class="btnMeeting btn btn-primary"><i class="fas fa-play-circle"></i> Join Meeting</button>
      </div>
    </div>
  </div>
</div> -->
@include('modal.admin.scheduleconsult')
@endsection
@section('js')
    @include('admin.scripts.teleconsult')
@endsection

