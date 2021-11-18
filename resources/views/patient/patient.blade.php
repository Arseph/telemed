@extends('layouts.app')

@section('content')
<style>
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="pull-right">
                <form action="{{ asset('patient/list') }}" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group-lg" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="keyword" placeholder="Search patient..." value="{{ Session::get("keyword") }}">
                        <button type="submit" class="btn btn-success btn-sm btn-flat">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type="submit" value="view_all" name="view_all" class="btn btn-warning btn-sm btn-flat">
                            <i class="fa fa-eye"></i> View All
                        </button>
                        <a data-toggle="modal" class="btn btn-info btn-sm btn-flat" data-target="#patient_modal">
                            <i class="fas fa-head-side-mask"></i> Add Patient
                        </a>
                    </div>
                </form>
            </div>
            <h3>List of Patients</h3>
        </div>
        <div class="box-body">
            @if(count($data)>0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tr class="bg-black">
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age / DOB</th>
                            <th>Barangay</th>
                            <th>Contact</th>
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
                                       onclick="getDataFromData(this)" 
                                    >
                                        {{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}
                                    </a>
                                </b>
                            </td>
                            <td>{{ $row->sex }}</td>
                            <td>
                                <b>{{ $row->dob }}</b><br>
                                <small class="text-success">
                                    <?php echo
                                    \Carbon\Carbon::parse($row->dob)->diff(\Carbon\Carbon::now())->format('%y years and %m months old');
                                    ?>
                                </small>
                            </td>
                            <td>{{ $row->brgy }}</td>
                            <td>{{ $row->contact }}</td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="text-center">
                    {{ $data->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <span class="text-warning">
                        <i class="fa fa-warning"></i> No Category found!
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
    @include('modal.patient.patientmodal')
@endsection
@section('js')
    @include('patient.scripts.patient')
@endsection

