save@extends('layouts.login')

@section('content')
<style>
    .card-reg {
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: 0.3s;
      width: 100%;
      border-radius: 10px;
      height: 600px;
      overflow: auto;
      overflow-x: hidden;
    }
    .main-content {
        padding: 20px;
    }
    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
    [class^='select2'] {
      border-radius: 0px !important;
      border: 0 !important;
    }
    .hide {
        display: none;
    }
</style>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-4 text-center">
          <span> <img src="{{ asset('public/img/doh.png') }}" style="width: 25%"/>&nbsp;
          <img src="{{ asset('public/img/dohro12logo2.png') }}" style="width: 25%"/>
        </div>
        <div class="mb-4 text-center">
          <span class="text-muted">DOH-CHD XII SOCCSKSARGEN TELECONSULTATION</span>
          <span class="d-block text-center my-4 text-muted">&mdash; Created by: DOH Region XII &mdash;</span>
        </div>
        <img src="{{ asset('public/img/Doctor_Online_Consultation.png') }}" alt="Image" class="img-fluid">
      </div>
      <div class="col-md-8 contents">
        <div class="text-center">
          <h4>REGISTER</h4>
        </div>
        <div class="text-right mb-3">
          <a href="{{asset('/login')}}">Back to Login ⇒</a>
        </div>
        <div class="card-reg">
            <div class="main-content">
                <form id="register_form" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12">
                        <label>Facility:</label>
                        <select class="form-control select2 selectFacility" name="facility_id" required>
                            <option value="">Select Facility ...</option>
                              @foreach($facilities as $fac)
                                <option value="{{ $fac->id }}">{{ $fac->facilityname }}</option>
                             @endforeach 
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label>Doctor Category:</label>
                        <select class="form-control select2 selectCat" name="doc_cat_id" required>
                            <option value="">Select Doctor Category ...</option>
                              @foreach($doccat as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->category_name }}</option>
                             @endforeach 
                        </select>
                    </div>
                    <div class="select-doctor col-sm-12">
                        <label>Doctor:</label>
                        <select class="form-control select2 selectDoctor" name="doctor_id" required>
                        </select>
                    </div>
                    <div class="col-sm-12"><hr></div>
                    <div class="col-sm-6">
                        <label>PhilHealth Status:</label>
                        <select class="form-control select_phic select21" name="phic_status" required>
                            <option value="None">None</option>
                            <option value="Member">Member</option>
                            <option value="Dependent">Dependent</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>PhilHealth ID:</label>
                        <input type="text" class="form-control phicID" value="" name="phic_id" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label>First Name:</label>
                        <input type="text" class="form-control" value="" name="fname" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Middle Name:</label>
                        <input type="text" class="form-control" value="" name="mname">
                    </div>
                    <div class="col-sm-6">
                        <label>Last Name:</label>
                        <input type="text" class="form-control" value="" name="lname" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Contact Number:</label>
                        <input type="text" class="form-control" value="" name="contact" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Birth Date:</label>
                        <input type="date" class="form-control" value="" min="1910-05-11" max="{{ date('Y-m-d') }}" name="dob" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Sex:</label>
                        <select class="form-control sex select21" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Civil Status:</label>
                        <select class="form-control civil_status select21" name="civil_status" required>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Occupation:</label>
                        <input type="text" class="form-control" value="" name="occupation">
                    </div>
                    <div class="col-sm-6">
                        <label>Select ID:</label>
                        <select class="form-control passport_no select21" name="passport_no" required>
                            <option value="umid">UMID</option>
                            <option value="dl">DRIVER'S LICENSE</option>
                            <option value="passport">PASSPORT ID</option>
                            <option value="postal">POSTAL ID</option>
                            <option value="tin">TIN ID</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label id="selectID">CRN:</label>
                        <input id="idVal" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Nationality:</label>
                        <select class="form-control select2" name="nationality_id" required>
                            <option value="{{ $nationality_def->num_code }}" selected>{{ $nationality_def->nationality }}</option>
                              @foreach($nationality as $n)
                                <option value="{{ $n->num_code }}">{{ $n->nationality }}</option>
                                 @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>House no./Lot/Bldg:</label>
                        <input type="text" class="form-control" value="" name="house_no">
                    </div>
                    <div class="col-sm-6">
                        <label>Street:</label>
                        <input type="text" class="form-control" value="" name="street">
                    </div>
                    <div class="col-sm-6">
                        <label>Region:</label>
                        <select class="form-control select21" name="region" id="region" required>
                            <option value="{{ $region->id }}" selected>{{ $region->reg_desc }}</option>
                            <option value="">Select Region...</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Province:</label>
                        <select class="form-control select21" name="province" id="province">
                            <option value="" selected>Select Province...</option>
                            @foreach($province as $prov)
                            <option value="{{ $prov->prov_psgc }}">{{ $prov->prov_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Municipality:</label>
                        <select class="form-control muncity filter_muncity select2" name="muncity" id="municipality" required>
                            <option value="">Select Municipal/City...</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <div class="barangay_holder">
                            <label>Barangay:</label>
                            <select class="form-control barangays select2" name="brgy" required>
                                <option value="">Select Barangay...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="has-group others_holder hide">
                             <label>Complete Address :</label>
                            <input type="text" name="address" class="form-control others" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr>
                        <label>Email Address:</label>
                        <input type="email" class="form-control email" id="email" name="email" value="" required>
                        <div class="email-has-error text-bold text-danger hide">
                            <small>Email already taken!</small>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label>Username:</label>
                        <input type="text" class="form-control username" id="username" value="" name="username" pattern=".{8,}" required>
                        <div class="username-has-error text-bold text-danger hide">
                            <small>Username already taken!</small>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label>Password:</label>
                        <input type="password" class="form-control pwd" name="password" pattern=".{8,}" required>
                    </div>
                </div>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Sign In</button>
                </div>
                </form>
            </div>
        </div> 
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
@if(Session::get('action_made'))
    Lobibox.notify('success', {
        title: "",
        msg: "<?php echo Session::get('action_made'); ?>",
        size: 'mini',
        rounded: true
    });
    <?php
        Session::put("action_made",false);
    ?>
@endif
  $(document).ready(function() {
      $(".select2").select2();
      $(".select21").select2({
        minimumResultsForSearch: -1
      });
  });
  $('.select_phic').on('change',function(){
    var status = $(this).val();
    if(status=='None'){
        $('.phicID').attr('disabled',true);
    }else{
        $('.phicID').val('').attr('disabled',false);
    }
  });
  $('#register_form').on('submit',function(e){
        e.preventDefault();
        if($(".email-has-error").hasClass("hide") && $(".username-has-error").hasClass("hide")) {
            $('#register_form').ajaxSubmit({
                url:  "{{ asset('register-account') }}",
                type: "POST",
                success: function(data){
                    setTimeout(function(){
                        window.location.reload(false);
                    },500);
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
        }
    });
    $('.passport_no').change(function() {
        switch(this.value) {
          case 'umid':
            $('#selectID').html('CRN:');
            break;
          case 'dl':
            $('#selectID').html('License No:');
            break;
          case 'passport':
            $('#selectID').html('Passport No:');
            break;
          case 'postal':
            $('#selectID').html('PRN:');
            break;
          case 'tin':
            $('#selectID').html('TIN No:');
            break;
        }
    });
     $('#province').on('change', function() {
        var id = this.value;
        if(id) {
            $.ajax({
                url: "{{ url('places') }}/"+id+"/municipality",
                method: 'GET',
                success: function(result) {
                    $('#municipality').empty()
                    .append($('<option>', {
                        value: '',
                        text : 'Select Municipality...'
                    }));
                    $.each(result.municipal,function(key,value){
                        $('#municipality').append($("<option/>", {
                           value: value.muni_psgc,
                           text: value.muni_name
                        }));
                    });
                }
            });
        }
    });
     var muncity_id = 0;
    $('.filter_muncity').on('change',function(){
        muncity_id = $(this).val();
        if(muncity_id!='others' && $(this).val()!=''){
            $('.filter_muncity').val(muncity_id);
            var brgy = getBarangay();
            $('.barangays').empty()
                .append($('<option>', {
                    value: '',
                    text : 'Select Barangay...'
                }));
            $.each(brgy.barangay, function(i,val){
                $('.barangays').append($('<option>', {
                    value: val.brg_psgc,
                    text : val.brg_name
                }));

            });
            $('.barangay_holder').show();
            $('.barangays').attr('required',true);
            $('.others_holder').addClass('hide');
            $('.others').attr('required',false);
        }else{
            $('.barangay_holder').hide();
            $('.barangays').attr('required',false);
            $('.others_holder').removeClass('hide');
            $('.others').attr('required',true);
        }
    });
    function getBarangay()
    {
        var url = "{{ url('places') }}";
        var tmp;
        $.ajax({
            url: url+"/"+muncity_id+"/barangay",
            type: 'GET',
            async: false,
            success : function(data){
                tmp = data;
            }
        });
        return tmp;
    }
    $('.selectCat').on('change',function(){
        var facility_id = $('.selectFacility').val();
        var cat_id = $(this).val();
        var doc = getDoctor(facility_id, cat_id);
        if(doc.doctors.length > 0) {
            $('.select-doctor').removeClass('hide');
            $('.selectDoctor').empty()
                .append($('<option>', {
                    value: '',
                    text : 'Select Doctor...'
                }));
            $.each(doc.doctors, function(i,val){
                $('.selectDoctor').append($('<option>', {
                    value: val.id,
                    text : val.lname + ', '+ val.fname + ' '+val.mname
                }));
            });
        } else {
            Lobibox.notify('error', {
                title: "",
                msg: "No doctors found in this category.",
                size: 'mini',
                rounded: true
            });
        }


    });
    function getDoctor(id, cat_id)
    {
        var url = "{{ url('get-doctor') }}";
        var tmp;
        $.ajax({
            url: url+"/"+id+"/"+cat_id,
            type: 'GET',
            async: false,
            success : function(data){
                tmp = data;
            }
        });
        return tmp;
    }

    $( "#email" ).on('blur',function() {
        var email = $(this).val();
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (filter.test(email)) {
            var url = "{{ url('validate-email') }}";
            var tmp;
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    email: email
                },
                async: false,
                success : function(data){
                    if(data.length > 0 ) {
                        $('.email-has-error').removeClass('hide');
                    } else {
                        $('.email-has-error').addClass('hide');
                    }
                }
            });
        }
    });
    $( "#username" ).on('blur',function() {
        var username = $(this).val();
        var url = "{{ url('validate-username') }}";
        var tmp;
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                username: username
            },
            async: false,
            success : function(data){
                if(data.length > 0 ) {
                    $('.username-has-error').removeClass('hide');
                } else {
                    $('.username-has-error').addClass('hide');
                }
            }
        });
    });
</script>
@endsection