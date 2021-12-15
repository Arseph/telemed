<?php
$dateNow = date('Y-m-d');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DOH CHD XII – Tele Consultation</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/AdminLTE.min.css') }}">
    <link rel="icon" href="{{ asset('public/img/dohro12logo2.png') }}">
  </head>
  <body class="hold-transition login-page">
   <div class="login-box">
        <center>
           <span> <img src="{{ asset('public/img/doh.png') }}" style="width: 25%"/>
            <img src="{{ asset('public/img/dohro12logo2.png') }}" style="width: 25%"/><br>
            <label style="font-size: 9pt;">DOH-CHD XII SOCCSKSARGEN</label>
            <label style="font-size: 9pt;">Tele Consultation</label></span>
        </center>
          <form role="form" method="POST" action="{{ asset('login') }}" class="form-submit" >
              {{ csrf_field() }}
              <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                  <div class="form-group has-feedback {{ Session::has('error') ? ' has-error' : '' }}">
                    <input id="username" autocomplete="off" type="text" placeholder="Login ID" autofocus class="form-control" name="username" value="{{ Session::get('username') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <span class="help-block">
                        @if($errors->any())
                            <strong style="color: #A52A2A;">{{$errors->first()}}</strong>
                        @endif
                    </span>
                  </div>
                  <div class="form-group has-feedback ">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="form-group">

                            </div>
                            <a target="_blank" href="#"> Click me to Register </a>
                        </div><!-- /.col -->
                        <div class="col-xs-5">
                            <button type="submit" class="btn btn-primary btn-block btn-flat btn-submit">
                                <i class="fa fa-lock"></i>&nbsp;&nbsp;Sign In
                            </button>
                            
                        </div><!-- /.col -->
                       
                    </div>
                </div><!-- /.login-box-body -->
                <div style="text-align: center;">
                <label style="font-size: 7pt; ">Created by: DOH Region XII</label>
              </div>
          </form>
          
         
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <script>
        $('.btn-submit').on('click',function(){
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Validating...');

        });

    </script>
  </body>
</html>