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
                <a data-toggle="modal" class="btn btn-success btn-lg" data-target="#meeting_modal">
                    <i class="far fa-calendar-plus"></i> Schedule Meeting
                </a>
            </div>
            <h3>My Meetings</h3>
        </div>
        <div class="box-body">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#upcoming">Upcoming</a></li>
              <li><a data-toggle="tab" href="#completed">Completed</a></li>
            </ul>

            <div class="tab-content">
              <div id="upcoming" class="tab-pane fade in active">
                <h3>Upcoming</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('doctor/teleconsult') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group-lg" style="margin-bottom: 10px;">
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
                                          <b class="text-primary">{{ $row->title }}</b>
                                          <br>
                                          <b>Patient: {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</b>
                                        </td>
                                        <td>
                                          <a href="#" class="btn-circle btn-primary" onclick="startMeeting('<?php echo $row->meetID?>')" target="_blank">
                                              <i class="fas fa-play-circle"></i> Start Meeting
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
              <!-- COmpleted Meetings -->
              <div id="completed" class="tab-pane fade">
                <h3>Completed</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ asset('doctor/teleconsult') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group-lg" style="margin-bottom: 10px;">
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
    @include('modal.doctors.teleconsultModal')
@endsection
@section('js')
    @include('doctors.scripts.teleconsult')
@endsection

