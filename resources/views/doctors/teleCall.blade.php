<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('public/img/dohro12logo2.png') }}">
    <meta http-equiv="cache-control" content="max-age=0" />
    <title>DOH CHD XII – Tele Consultation System</title>
    <!-- <title>{{ (isset($title)) ? $title : 'Referral System'}}</title> -->
    <!-- SELECT 2 -->
    <link href="{{ asset('public/plugin/select2/select2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/bootstrap-theme.min.css') }}" rel="stylesheet">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('public/plugin/Ionicons/css/ionicons.min.css') }}">

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/dad1cf763f.js" crossorigin="anonymous"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('public/assets/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css/AdminLTE.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap-clockpicker.min.css') }}">
    <link href="{{ asset('public/plugin/datepicker/datepicker3.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/plugin/Lobibox/lobibox.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('public/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link href="{{ asset('public/plugin/daterangepicker_old/daterangepicker-bs3.css') }}" rel="stylesheet">

    <link href="{{ asset('public/plugin/table-fixed-header/table-fixed-header.css') }}" rel="stylesheet">
    <title>
        @yield('title','Home')
    </title>

    @yield('css')
    <style>
        body {
            background: url('{{ asset('public/img/backdrop.png') }}'), -webkit-gradient(radial, center center, 0, center center, 460, from(#ccc), to(#ddd));
        }
        .loading {
            background: rgba(255, 255, 255, 0.9) url('{{ asset('public/img/loading.gif')}}') no-repeat center;
            position:fixed;
            width:100%;
            height:100%;
            top:0px;
            left:0px;
            z-index:999999999;
            display: none;
        }

        #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: rgba(38, 125, 61, 0.92);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 4px;
        }
        #myBtn:hover {
            background-color: #555;
        }
        .select2 {
            width:100%!important;
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
          width: 30%;
          display: inline-block;
          text-align: left;
          vertical-align: middle;
        }
        .hangup {
            background-color: red;
            color: #FFF;
            border-radius: 100%;
            text-align: center;
            font-size: 30px;
            border-style: hidden;
        }
        .mic {
            background-color: gray;
            color: #FFF;
            border-radius: 100%;
            text-align: center;
            font-size: 100%;
            border-style: hidden;
        }
        #self-view {
            position: fixed;
            width: 15%;
            right: 10px;
        }
        #remote-view-video {
            width: 100%;
            height: 505px;
        }
        @media only screen and (max-width: 800px) {
            #self-view {
                position: inherit;
                width: 100%;
            }
            #remote-view-video {
                width: 100%;
                height: 100%;
            }
        }
        label {
            padding: 0px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .vertical-scrollable{
          height:399px;
          overflow-y: scroll; 
          overflow-x: hidden;
        }
    </style>
</head>

<body>

<!-- Fixed navbar -->

<nav class="navbar navbar-default fixed-top" >
    <div class="header" style="background-color:#2F4054;padding:10px;">
        <meta id="webex-token" content="{{ env('WEBEX_API') }}">
        <div>
            <div class="col-md-4">
                <div class="pull-left">
                    <span class="title-info">{{ $meeting->title }}</span><br>
                    <b style="color: white;">Teleconsultation with:</b> <span style="color: white;">{{ $meeting->lname }}, {{ $meeting->fname }} {{ $meeting->mname }} </span>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</nav>
<div id="app">
    <main class="py-4">
        <form method="POST">
        <div id="contentTele" class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="pull-right">
                            <button type="button" title="save" class="btnSaveall btn btn-success">Save</button>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">Demographic Profile</a></li>
                            <li><a href="#tab2" data-toggle="tab">Clinical History and Physical Examination</a></li>
                            <li><a href="#tab3" data-toggle="tab">Covid-19 Screening</a></li>
                            <li><a href="#tab4" data-toggle="tab">Diagnosis/Assessment</a></li>
                            <li><a href="#tab5" data-toggle="tab">Plan of Management</a></li>
                        </ul>
                        <div class="tab-content vertical-scrollable">
                            <div class="tab-pane active" id="tab1">
                                <div class="box box-success">
                                    <div class="box-header with-border" style="background-color: #00a65a; color: white;">
                                        <h4 style="">Demographic Profile</h4>
                                    </div>
                                    <div class="box-body">
                                        @include('forms.demographic')
                                    </div>
                                </div>
                                <div class="box box-success">
                                    <div class="box-header with-border" style="background-color: #00a65a; color: white;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 style="">Patient Profile</h4>
                                            </div>
                                            <div class="col-md-6 form-inline">
                                                <div class="form-group">
                                                    <label>Case #:</label>
                                                    <input type="text" class="form-control" value="{{ $case_no }}" name="case_no" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @include('forms.patientprof')
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="box box-success">
                                    <div class="box-header with-border" style="background-color: #00a65a; color: white;">
                                        <h4 style="">Clinical History</h4>
                                    </div>
                                    <div class="box-body">
                                        @include('forms.clinical')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
                                @include('forms.covid')
                            </div>
                            <div class="tab-pane" id="tab4">
                                @include('forms.diagnosis')
                            </div>
                            <div class="tab-pane" id="tab5">
                                @include('forms.plan')
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="pull-right">
                                <a title="Previous" class="btn btnPrevious hide" >Previous &nbsp;<i class="fas fa-less-than"></i></a>
                                <a title="Next" class="btn btnNext" >Next &nbsp;<i class="fas fa-greater-than"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                  <div class="pull-right">
                      <video id="self-view" autoplay></video>
                  </div>
                  <div style="width:100%; height: 100%;">
                    <audio id="remote-view-audio" autoplay></audio>
                    <video id="remote-view-video" autoplay></video>
                  </div>
                  <div class="text-center">
                      <!-- <button id="turnoffcamera" title="turn off camera" type="button" class="mic"><small><i class="fas fa-video"></i></small></button>
                      <button id="turnoffmic" title="turn off mic" type="button" class="mic"><i class="fas fa-microphone"></i></button> -->
                      <button id="hangup" title="hangup" type="button" class="hangup"><small><i class="fas fa-phone-alt"></i></small></button>&nbsp;
                  </div>
                </div>
            </div>
        </div>
        </form>
    </main>
</div>

<div class="modal fade" id="meeting_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title title-info" id="myModalLabel">{{ $meeting->title }}</h4>
      </div>
      <div class="modal-body">
        <form id="destination">
          <input
            id="invitee"
            name="invitee"
            placeholder="Person ID or Email Address or SIP URI or Room ID"
            type="hidden"
            value="{{ $meeting->web_link }}"
           />
           <div class="text-center">
            <h3>Meeting with:</h3> <h3><b>{{ $meeting->lname }}, {{ $meeting->fname }} {{ $meeting->mname }}</b> </h3><br><br>
            <label class="tired hide">Tired of waiting? Try reload the page</label><br>
            <button type="submit" id="join" title="join" class="btnJoin btn btn-success" onclick="joining()" disabled><i class="fa fa-spinner fa-spin"></i> Loading SDK...</button>
           </div>
        </form>
      </div>
    </div>
  </div>
</div>

<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>
<footer class="footer">
    <div class="container">
        <p class="pull-right">All Rights Reserved {{ date("Y") }} | Version 1.0</p>
    </div>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script crossorigin src="https://unpkg.com/webex@^1/umd/webex.min.js"></script>
<script src="{{ asset('resources/views/doctors/scripts/webex_function.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.min.js?v='.date('mdHis')) }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('public/plugin/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap-clockpicker.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery-validate.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('public/assets/js/ie10-viewport-bug-workaround.js') }}"></script>
<script src="{{ asset('public/assets/js/script.js') }}?v=1"></script>

<script src="{{ asset('public/plugin/Lobibox/Lobibox.js') }}?v=1"></script>
<script src="{{ asset('public/plugin/select2/select2.min.js') }}?v=1"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('public/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}?v=1"></script>

<script src="{{ url('public/plugin/daterangepicker_old/moment.min.js') }}"></script>
<script src="{{ url('public/plugin/daterangepicker_old/daterangepicker.js') }}"></script>

<script src="{{ asset('public/assets/js/jquery.canvasjs.min.js') }}?v=1"></script>

<!-- TABLE-HEADER-FIXED -->
<script src="{{ asset('public/plugin/table-fixed-header/table-fixed-header.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    $(document).ready(function() {
        window.onbeforeunload = function() {
        return "Are you sure you want to leave?";
    }
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('.daterange').daterangepicker({
            "singleDatePicker": true
        });
        $(".select2").select2();
        // $('#meeting_modal').modal('show');
        setTimeout(function(){
            $('.btnJoin').prop("disabled", false);
            $('.btnJoin').html('<i class="far fa-play-circle"></i> Start');
        }, 7000);
    });
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }
    function topFunction() {
        $('body,html').animate({
            scrollTop : 0
        }, 500);
    }
    function joining() {
        setTimeout(function(){ $('.tired').removeClass('hide'); }, 15000);
        $('.btnJoin').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
    }
    var val = 0;
     $('.btnNext').click(function(){
       val++;
       $('.nav-tabs > .active').next('li').find('a').trigger('click');
       if(val > 0) {
         $('.btnPrevious').removeClass('hide');
       }
       if(val >= 4) {
        $(this).addClass('hide');
       }
    });
    $('.btnPrevious').click(function(){
      val--;
      if(val <= 0) {
        $(this).addClass('hide');
      }
      if(val > 0) {
         $('.btnNext').removeClass('hide');
       }
      $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    $('input[type=radio][name=is_patient_accompanied]').change(function() {
    if (this.value == 'Yes') {
        $('#companion').removeClass('hide');
    }
    else if (this.value == 'No') {
        $('#companion').addClass('hide');
    }
});

</script>

@yield('js')
@include('admin.scripts.patient')
</body>
</html>