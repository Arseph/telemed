@extends('layouts.app')

@section('content')
    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3>
                    Issue and Concern
                    <form action="{{ asset('issue/concern') }}" method="POST" class="form-inline pull-right" style="margin-right: 30%">
                        {{ csrf_field() }}
                        <div class="form-group-sm">
                            <input type="text" class="form-control active" name="date_range"  placeholder="Filter your daterange here..." id="consolidate_date_range" autocomplete="off">
                            <button type="submit" class="btn-sm btn-info btn-flat" onclick="loadPage();"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </form>
                </h3>
            </div>
            @if(count($data)>0)
            <div class="table-responsive">
                <table class="table table-striped table-responsive">
                    <tr>
                        <th></th>
                        <th>Date Referred</th>
                        <th>Referring Facility</th>
                        <th>Referred To</th>
                        <th></th>
                    </tr>
                    @foreach($data as $row)
                        <tr>
                           
                        </tr>
                    @endforeach
                </table>
            </div>
            @else
                <div class="alert alert-warning">
                    <span class="text-warning">
                        <i class="fa fa-warning"></i> No Issue found!
                    </span>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" role="dialog" id="add_remark">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body monitoring_remark">

                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@include('modal.doctors.issueModal')

@endsection

@section('js')
    <script>
       
        $('#consolidate_date_range').daterangepicker();

        @if(Session::get('add_remark'))
        Lobibox.notify('success', {
            title: "",
            msg: "Successfully added ramark!",
            size: 'mini',
            rounded: true
        });
        <?php Session::put("add_remark",false); ?>
        @endif
    </script>
@endsection

