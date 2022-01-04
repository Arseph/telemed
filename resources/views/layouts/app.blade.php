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
    </style>
</head>

<body>

<!-- Fixed navbar -->

<nav class="navbar navbar-default fixed-top" >
    <div class="header" style="background-color:#2F4054;padding:10px;">
        <div>
            <div class="col-md-4">
                <div class="pull-left">
                    <?php
                    $user = Session::get('auth');
                    $t = '';
                    $dept_desc = '';
                    if($user->level=='doctor')
                    {
                        $t='Dr.';
                    }else if($user->level=='patient'){
                        $dept_desc = ' / Patient';
                    }
                    $requestedPatient = \App\Patient::select(
                        "patients.*",
                        "bar.brg_name as barangay",
                        "user.email as email",
                        "user.username as username",
                    ) ->leftJoin("barangays as bar","bar.brg_psgc","=","patients.brgy")
                    ->leftJoin("users as user","user.id","=","patients.account_id")
                    ->where('patients.doctor_id', $user->id)
                    ->where('user.doctor_id', $user->id)
                    ->where('patients.is_accepted', 0)
                    ->get();
                    ?>
                    <span class="title-info">Welcome,</span> <span class="title-desc">{{ $t }} {{ $user->fname }} {{ $user->lname }} {{ $dept_desc }}</span>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="header" style="background-color:#59ab91;padding:10px;">
        <div class="container">
            <img src="{{ asset('public/img/referral_banner4.png') }}" class="img-responsive" />
        </div>
    </div>
    <div class="container-fluid" >
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" style="font-size: 13px;">
            <ul class="nav navbar-nav">
                @if($user->level=='superadmin')
                <li><a href="{{ asset('superadmin') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-book"></i>&nbsp; Library <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a href="{{ asset('/diagnosis') }}" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false"> <span class="nav-label"><i class="fas fa-chart-line"></i>&nbsp; Diagnosis</span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ asset('/diagnosis-main-category') }}"><i class="fas fa-th-large"></i>&nbsp; Main Category</a></li>
                                <li><a href="{{ asset('/diagnosis-sub-category') }}"><i class="fas fa-th"></i>&nbsp; Sub Category</a></li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fas fa-medkit"></i>&nbsp; Drugs/Meds</a></li>
                        <li><a href="#"><i class="fas fa-chart-area"></i>&nbsp; Demographic</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i>&nbsp; Manage <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ asset('/users') }}"><i class="fas fa-users"></i>&nbsp; Users</a></li>
                        <li><a href="#"><i class="fas fa-user-check"></i>&nbsp; User Approval</a></li>
                        <li><a href="#"><i class="fa fa-list-alt"></i>Role/Permission</a></li>
                        <li><a href="{{ asset('facilities') }}"><i class="fa fa-hospital-o"></i>&nbsp; Facilities</a></li>
                        <li><a href="{{ asset('provinces') }}"><i class="fa fa-hospital-o"></i>&nbsp; Province</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="nav-label"><i class="fa fa-hospital-o"></i>&nbsp;&nbsp;&nbsp; Municipality</span></a>
                            <ul class="dropdown-menu">
                                @foreach(\App\Province::get() as $prov)
                                    <li><a href="{{ url('municipality').'/'.$prov->prov_psgc.'/'.$prov->prov_name }}">{{ $prov->prov_name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="far fa-newspaper"></i> Reports <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-file-o"></i> Monitoring Report</a></li>
                        <li><a href="#"><i class="fa fa-user-md"></i> Audit Trail</a></li>
                        <li><a href="#"><i class="fa fa-comments"></i>Feedback</a></li>
                    </ul>
                </li>
                @endif
                <!-- for admin -->
                @if($user->level=='admin')
                <li><a href="{{ asset('admin') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i>&nbsp; Manage <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fas fa-user-md"></i>&nbsp; Doctors</a></li>
                        <li><a href="{{ asset('/admin-facility') }}"><i class="fas fa-hospital"></i>&nbsp; Facility</a></li>
                        <li><a href="{{ asset('/admin-patient') }}"><i class="fas fa-head-side-mask"></i>&nbsp; Patients</a></li>
                        <li><a href="#"><i class="fas fa-video"></i>&nbsp; Teleconsultation</a></li>
                    </ul>
                </li>
                @endif
                <!-- for doctors -->
                @if($user->level=='doctor')
                <li><a href="{{ asset('doctor') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="{{ asset('doctor/teleconsult') }}"><i class="fas fa-video"></i> Teleconsultation</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-list-alt"></i>&nbsp; Management <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ asset('doctor/patient/list') }}"><i class="far fa-address-card"></i> Patient <span class="badge bg-red">{{ count($requestedPatient) }}</span></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-paste"></i>&nbsp; Reports <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fas fa-exchange-alt"></i> Daily Transaction</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i>&nbsp; Settings <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" data-toggle="modal" data-target="#webex_modal"><i class="fas fa-coins"></i> Webex Token</a></li>
                        <li><a href="#"><i class="fas fa-key"></i> Change Password</a></li>
                    </ul>
                </li>
                @endif
                @if($user->level=='patient')
                <li><a href="{{ asset('patient') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-notes-medical"></i> Medical Records & Attachments</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i>&nbsp; Settings <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fas fa-key"></i> Change Password</a></li>
                    </ul>
                </li>
                @endif
                <!-- For doctors and rhu -->
                @if($user->level=='doctor' || $user->level=='admin')
                <li><a href="#"><i class="fas fa-comment-dots"></i> Feedback</a></li>
                @endif
                <li><a href="{{ asset('logout') }}"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div id="app">
    <main class="py-4">
        @yield('content')
    </main>
</div>

<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>
<footer class="footer">
    <div class="container">
        <p class="pull-right">All Rights Reserved {{ date("Y") }} | Version 1.0</p>
    </div>
</footer>
<div class="modal fade" id="webex_modal" role="dialog" aria-labelledby="webex_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <form id="webex_form" method="POST">
        {{ csrf_field() }}
        <small>Get your personal access webex token </small><a href="https://developer.webex.com/docs/getting-started" target="_blank">here</a><br>
        <div class="form-group">
            <label>Your Personal Access Token:</label>
            <input type="password" class="form-control" value="" name="webextoken" placeholder="Paste here..." required>
        </div>
        <small style="color: red;">Note: Please change your webex token every 12 hours.</small>
      <div class="modal-footer">
        <button type="submit" class="btnSaveWebex btn btn-success"><i class="fas fa-check"></i> Save</button>
    </form>
      </div>
    </div>
  </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
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

<script>
    $(document).ready(function() {
        $(".select2").select2();
        var user = {!! json_encode($user) !!};
        if(user.level == 'doctor') {
            Lobibox.notify('info', {
                size: 'large',
                title: 'Webex Token',
                msg: 'Remember to change your webex token every 12 hours.'
            }); 
        }
    });
    function refreshPage(){
        <?php
            use Illuminate\Support\Facades\Route;
            $current_route = Route::getFacadeRoot()->current()->uri();
        ?>
        $('.loading').show();
        window.location.replace("<?php echo asset($current_route) ?>");
    }

    function loadPage(){
        $('.loading').show();
    }
    //Get the button
    var mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        $('body,html').animate({
            scrollTop : 0 // Scroll to top of body
        }, 500);
    }

    $('#webex_form').on('submit',function(e){
        e.preventDefault();
        $('.btnSaveWebex').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#webex_form').ajaxSubmit({
            url:  "{{ url('/webex-token') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
    });

</script>

@yield('js')

</body>
</html>