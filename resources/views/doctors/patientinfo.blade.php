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
    .image-center {
        width: 35%;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    .heading-info {
        font-weight: bold;
        background: white;
        margin-top: 20px;
        color: #c93600;
        padding: 3px 7px;
        border-top: 1px solid #c93600;
        border-bottom: 1px solid #c93600;
        width: 100%;
        margin-bottom: 20px;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<input type="hidden" id="patient_id" value="{{ $patient->id }}">
<div class="container-fluid">
    <div class="box box-success">
        <div class="row">
            <div class="col-md-3">
                @if($patient->sex=='Male')
                <img src="{{ asset('public/img/mans.png') }}" class="image-center" />
                @else
                <img src="{{ asset('public/img/womans.png') }}" class="image-center" />
                @endif
                <div><label class="heading-info">Patient Information</label></div>
                <div><label style="text-transform: uppercase;">{{$patient->lname}}, {{$patient->fname}} {{$patient->mname}}</label></div>
                <div style="margin-bottom: 6px;">{{\Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->format('%y years and %m months old and %d day(s)')}}</div>
                <div>Birthdate: <label>{{\Carbon\Carbon::parse($patient->dob)->format('F d, Y')}}</label></div>
                <div>Sex: <label>{{$patient->sex}}</label></div>
                <div>Civil Status: <label>{{$patient->civil_status}}</label></div>
                <hr>
                <div>
                    <ul class="nav nav-pills nav-stacked" style="overflow: auto; height: 350px;">
                      <li><a data-toggle="tab" href="#tabspatientInfo"><img src="{{ asset('public/img/profile.png') }}"/>&nbsp;Profile</a></li>
                      <li class="active"><a data-toggle="tab" href="#tabsTele"><img src="{{ asset('public/img/tele.png') }}"/>&nbsp;Teleconsultations</a></li>
                      <li><a data-toggle="tab" href="#tabsMedHis"><img src="{{ asset('public/img/medhis.png') }}"/>&nbsp;Medical History</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div id="tabspatientInfo" class="tab-pane fade in">
                        <div class="pull-right">
                            <h4 id="btnEdit" title="Edit Facility" onclick="enableView()"><a href="javascript:void(0)"><i class="far fa-edit"></i></a></h4>
                        </div>
                        <h3>Patient Profile</h3>
                        @include('forms.patientprof')
                    </div>
                    <div id="tabsTele" class="tab-pane fade in active">
                        <h3>Teleconsultations</h3>
                        <br>
                        @include('doctors.tabs.consults')
                    </div>
                    <div id="tabsMedHis" class="tab-pane fade in">
                        <h3>Medical History</h3>
                    </div>
                    <div id="tabsTelDet" class="tab-pane fade in">
                        <h3>Teleconsultation Details</h3>
                        @include('doctors.tabs.details')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var meeting_id;
    var docorderid;
    function enableView() {
        $('#patient_form').removeClass('disAble');
        $( '.btnSave' ).removeClass('hide');
        $( '#btnEdit' ).addClass('hide');
        $( 'input[name="fname"]' ).focus();
        $( '.reqField' ).addClass('required-field');
    }
    function telDetail(id, view, tab, docid) {
        docorderid = docid ? docid : docorderid;
        var url = "{{ url('/tele-details') }}";
        view = view ? view : 'demographic';
        tab = tab ? tab : 'patientTab';
        meeting_id = id ? id : meeting_id;
        var urlmet = "{{ url('/meeting-info') }}";
        $('#'+tab).html('loading...');
        $.ajax({
            async: true,
            url: urlmet,
            type: 'GET',
            data: {
                meet_id: meeting_id,
            },
            success : function(data){
                var val = JSON.parse(data);
                if(val) {
                    var time = moment(val['date_meeting']).format('MMMM D, YYYY')+' '+moment(val['from_time'], "HH:mm:ss").format('h:mm A')+' - '+moment(val['to_time'], "HH:mm:ss").format('h:mm A');
                    $('#caseNO').html(val['caseNO']);
                    $('input[name="dateandtime"]').val(time);
                }
            }
        });
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
            data: {
                meet_id: meeting_id,
                view: view,
                docorderid: docorderid
            },
            success : function(data){
                setTimeout(function(){
                    $('#'+tab).html(data);
                    make_base(document.getElementById('signature-pad'));
                    $('#companion').removeClass('hide');
                    $( '.btnAddrow' ).addClass('hide');
                    $( '.btnAddrowScrum' ).addClass('hide');
                    $( '.btnAddrowSwab' ).addClass('hide');
                    $( '.btnAddrowother' ).addClass('hide');
                    $('.ifCovid').removeClass('hide');
                    $( '.btnRemoveRow' ).addClass('hide');
                    $(".select2").select2();
                    if(tab == 'docTab') {
                        getDocorder();
                    }
                },500);
            }
        });
    }
    $('#patient_form').on('submit',function(e){
        e.preventDefault();
        $('#patient_form').ajaxSubmit({
            url:  "{{ url('/patient-store') }}",
            type: "POST",
            data: {
                patient_id: $('#patient_id').val()
            },
            success: function(data){
                $('#patient_form').addClass('disAble');
                $( '.btnSave' ).addClass('hide');
                $( '#btnEdit' ).removeClass('hide');
                Lobibox.notify('success', {
                    title: "",
                    msg: "Successfully save patient profile",
                    size: 'normal',
                    rounded: true
                });
            },
            error: function (data) {
                $(".loading").hide();
                Lobibox.notify('error', {
                    title: "",
                    msg: "Something went wrong, Please try again.",
                    size: 'normal',
                    rounded: true
                });
            },
        });

    });

    function make_base(is)
    {
        if(is) {
          var signa = $('input[name="signaturephy"]').val();
          var canvas = document.getElementById('signature-pad');
          context = document.getElementById('signature-pad') ? canvas.getContext('2d') : '';
          base_image = new Image();
          base_image.src = signa;
          base_image.onload = function(){
            context.drawImage(base_image, 0, 0);
          }
        }
    }

    function getDocorder() {
        var url = "{{ url('/doctor-order-info') }}";
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
            data: {
                docorderid: docorderid
            },
            success : function(data){
                var val = data.docorder;
                var labs = data.labreq;
                if(labs.length > 0) {
                    var html = '';
                    $.each( labs, function( key, value ) {
                        var files = "{{asset('public') }}"+"/"+ value.filepath;
                        html +='<a href="'+files+'" class="list-group-item">'+value.filename+'.'+value.extensionname+'</a>';
                    });
                    $('#listLabreq').html(html);
                }
                if(!val) {
                    Lobibox.notify('info', {
                    title: "",
                    msg: "Consultation doesn't have Doctor Order.",
                    size: 'mini',
                    rounded: true
                });
                } else {
                    var labreq = val.labrequestcodes.split(',');
                    var img = val.imagingrequestcodes.split(',');
                    $("#labrequestcodeslab").select2({multiple:true,});
                    $("#labrequestcodeslab").select2({multiple:true,});
                    $("#labrequestcodeslab").val(labreq).trigger('change');
                    $("#imagingrequestcodeslab").select2().val(img).trigger('change');
                }

            },
            error : function(data){
                $(".loading").hide();
                Lobibox.notify('error', {
                    title: "",
                    msg: "Something Went Wrong. Please Try again.",
                    size: 'mini',
                    rounded: true
                });
            }
        });

    }

</script>
@endsection

