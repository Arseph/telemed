@extends('layouts.app')

@section('content')
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
            <div class="pull-left">
                <form action="{{ asset('doctor/teleconsult') }}" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group-lg" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="date_range" value=""placeholder="Filter your date here..." id="consolidate_date_range">
                        <button type="submit" class="btn btn-info btn-sm btn-flat">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type="submit" value="view_all" name="view_all" class="btn btn-warning btn-sm btn-flat">
                            <i class="fa fa-eye"></i> View All
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-body">
            <p>ngi</p>
        </div>
    </div>
</div>
    @include('modal.doctors.teleconsultModal')
@endsection
@section('js')
    @include('doctors.scripts.teleconsult')
@endsection

