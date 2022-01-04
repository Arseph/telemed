@extends('layouts.app')

@section('content')
<style>
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .disAble {
        pointer-events:none;
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
          width: 80%;
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="pull-right">
                <form action="{{ asset('doctor/patient/list') }}" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group-lg" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="keyword" placeholder="Search patient..." value="{{ Session::get("keyword") }}">
                        <button type="submit" class="btn btn-success btn-sm btn-flat">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type="submit" value="view_all" name="view_all" class="btn btn-warning btn-sm btn-flat">
                            <i class="fa fa-eye"></i> View All
                        </button>
                        <!-- <a data-toggle="modal" class="btn btn-info btn-sm btn-flat" data-target="#patient_modal">
                            <i class="fas fa-head-side-mask"></i> Add Patient
                        </a> -->
                    </div>
                </form>
            </div>
            <h3>List of Patients</h3>
        </div>
        <div class="box-body">
            <div class="pull-right">
                <button data-toggle="modal" data-target="#request_modal" type="button" class="btn btn-primary position-relative">
                  Request
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-red">
                        {{ count($requested) }}
                   </span>
                </button>
            </div>
            <br>
            <br>
            @if(count($data)>0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tr class="bg-black">
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age / DOB</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th></th>
                        </tr>
                        
                        @foreach($data as $row)
                        <tr>
                            <td style="white-space: nowrap;">
                                <b>
                                    <a
                                       href="#"
                                       data-toggle="modal"
                                       data-id= "{{ $row->id }}"
                                       class="title-info update_info"
                                       data-target="#patient_modal" 
                                       onclick="getDataFromData(this, '<?php echo $row->account_id?>')" 
                                    >
                                        {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}
                                    </a>
                                </b>
                            </td>
                            <td>{{ $row->sex }}</td>
                            <td>
                                @if($row->dob)
                                <b><?php echo
                                    \Carbon\Carbon::parse($row->dob)->format('F d, Y');
                                    ?></b><br>
                                <small class="text-success">
                                    <?php echo
                                    \Carbon\Carbon::parse($row->dob)->diff(\Carbon\Carbon::now())->format('%y years and %m months old');
                                    ?>
                                </small>
                                @endif
                            </td>
                            <td>{{ $row->barangay }}</td>
                            <td>{{ $row->contact }}</td>
                            <td>
                                <a class="btn btn-info btn-sm btn-flat" onclick="meetingInfo('<?php echo $row->id?>', '1')"><i class="far fa-clock"></i>&nbsp;Schedule</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    
                </div>
            @else
                <div class="alert alert-warning">
                    <span class="text-warning">
                        <i class="fa fa-warning"></i> No Patients found!
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
    @include('modal.doctors.patientmodal')
@endsection
@section('js')
    @include('doctors.scripts.patient')
@endsection

