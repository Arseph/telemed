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
        .float{
            position: fixed;
            height: 25%;
            bottom: 7%;
            right: 0px;
        }
    </style>
</head>

<body>
<div class="header" style="background-color:#2F4054;padding:10px;">
    <input type="hidden" id="webex_token" value="{{ env('WEBEX_API') }}">
    <div>
        <div class="col-md-12">
            <div class="pull-left">
                <span class="title-info">{{ $meeting->title }}</span><br>
                <b style="color: white;">Meeting with:</b> <span style="color: white;">{{ $meeting->lname }}, {{ $meeting->fname }} {{ $meeting->mname }} </span>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<div id="app">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <h3>Patient Form</h3>
            </div>
        </div>
        <div class="col-md-8">
    <form id="destination">
      <input
        id="invitee"
        name="invitee"
        placeholder="Person ID or Email Address or SIP URI or Room ID"
        type="hidden"
        value="{{ $meeting->web_link }}"
       />
        <input class="" id="join" title="join" type="submit" value="join" />
    </form>

    <div>
      <div style="width:100%; height: 100%;">
        <audio id="remote-view-audio" autoplay></audio>
        <video style="height:100%; width: 100%" id="remote-view-video" autoplay></video>
      </div>
      <div class="pull-right">
          <video class="float" id="self-view" muted autoplay></video>
      </div>
    </div>

    <button id="hangup" title="hangup" type="button">cancel/hangup</button>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-right">All Rights Reserved {{ date("Y") }} | Version 1.0</p>
    </div>
</footer>

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

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
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

</script>

@yield('js')

</body>
</html>