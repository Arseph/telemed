<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/font-login-style.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/owl.carousel.min.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/css/login.min.css') }}">
    
    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('public/css/login-style.css') }}">
    <link rel="icon" href="{{ asset('public/img/dohro12logo2.png') }}">
    <title>DOH CHD XII – Tele Consultation</title>
  </head>
  <body>
  

  
  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img src="{{ asset('public/img/Doctor_Online_Consultation.png') }}" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4 text-center">
                <span> <img src="{{ asset('public/img/doh.png') }}" style="width: 25%"/>&nbsp;
                <img src="{{ asset('public/img/dohro12logo2.png') }}" style="width: 25%"/>
              </div>
              <div class="mb-4 text-center">
                <span class="text-muted">DOH-CHD XII SOCCSKSARGEN TELECONSULTATION</span>
              </div>
              <div class="text-center">
                <label>LOGIN</label>
              </div>
            <span class="help-block">
                @if($errors->any())
                    <strong style="color: #A52A2A;">{{$errors->first()}}</strong>
                @endif
            </span>
            <form method="POST" action="{{ asset('login') }}">
              {{ csrf_field() }}
              <div class="form-group first">
                <label for="username">Username</label>
                <input autofocus type="text" class="form-control" id="username" name="username" autocomplete="off">
              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                
              </div>

              <button type="submit" class="btn-submit btn btn-block btn-success">LOGIN</button>

              <span class="d-block text-center my-4 text-muted">&mdash; Created by: DOH Region XII &mdash;</span>
            </form>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

  
    <script src="{{ asset('public/js/login/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('public/js/login/popper.min.js') }}"></script>
    <script src="{{ asset('public/js/login/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/login/main.js') }}"></script>  
    <script>
        $('.btn-submit').on('click',function(){
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Validating...');

        });

    </script>
  </body>
</html>