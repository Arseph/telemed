<script>
	var patients = {!! json_encode($patients->toArray()) !!};
    var users = {!! json_encode($users->toArray()) !!};
    var user = {!! json_encode($user) !!};
	var toDelete;
    var invalidEmail;
    var invalidUsername;
    var processOne;
    var processTwo;
    var existUsername;
    $(document).ready(function() {
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('.daterange').daterangepicker({
            minDate: today,
            "singleDatePicker": true
        });
       $('.clockpicker').clockpicker({
            donetext: 'Done',
            twelvehour: true,
            afterDone: function() {
                validateTIme();
            }
       });
    });
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
    @if(Session::get('delete_action'))
        Lobibox.notify('error', {
            title: "",
            msg: "<?php echo Session::get('delete_action'); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
            Session::put("delete_action",false);
        ?>
    @endif
	$('.select_phic').on('change',function(){
        var status = $(this).val();
        if(status!='none'){
            $('.phicID').attr('disabled',false);
        }else{
            $('.phicID').val('').attr('disabled',true);
        }
    });

    var muncity_id = 0;
    $('.filter_muncity').on('change',function(){
        muncity_id = $(this).val();
        if(muncity_id!='others' && $(this).val()!=''){
            $('.filter_muncity').val(muncity_id);
            var brgy = getBarangay();
            $('.barangay').empty()
                .append($('<option>', {
                    value: '',
                    text : 'Select Barangay...'
                }));
            jQuery.each(brgy, function(i,val){
                $('.barangay').append($('<option>', {
                    value: val.brg_psgc,
                    text : val.brg_name
                }));

            });
            $('.barangay_holder').show();
            $('.barangay').attr('required',true);
            $('.others_holder').addClass('hide');
            $('.others').attr('required',false);
        }else{
            $('.barangay_holder').hide();
            $('.barangay').attr('required',false);
            $('.others_holder').removeClass('hide');
            $('.others').attr('required',true);
        }

    });

    function getBarangay()
    {
        $('.loading').show();
        var url = "{{ url('location/barangay') }}";
        var tmp;
        $.ajax({
            url: url+"/"+muncity_id,
            type: 'GET',
            async: false,
            success : function(data){
                tmp = data;
                setTimeout(function(){
                    $('.loading').hide();
                },500);
            }
        });
        return tmp;
    }
    $( "#deleteBtn" ).click(function() {
		toDelete = 'yes';
        var id = $("#patient_id").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:  "{{ url('/patient-delete') }}/"+id,
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
	});
    $('#patient_form').on('submit',function(e){
		e.preventDefault();
        $('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
		$('#patient_form').ajaxSubmit({
            url:  "{{ url('/patient-store') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
		
	});
	function getDataFromData(ele, id) {
		$("#myModalLabel").html('Update Patient');
    	$("#patient_id").val($(ele).data('id'));
    	$("#deleteBtn").removeClass("hide");
    	const edit = [];
    	$.each(patients, function(key, value) {
	        if(value.id == $(ele).data('id')) {
	        	edit.push(value);
	        }
	    });
        if(edit.length > 0 ) {
    	    $('.select_phic').val(edit[0].phic_status).change();
            if(user.level == 'admin') {
                $("#doctor_id").select2().select2('val', edit[0].doctor_id);
            }
            $("[name=region]").select2().select2('val', edit[0].region);
            $("[name=province]").select2().select2('val', edit[0].province);
    	    $("[name=muncity]").select2().select2('val', edit[0].muncity);
    	    $("[name=brgy]").select2().select2('val', edit[0].brgy);
    	    $("input[name=phic_id]").val(edit[0].phic_id);
    	    $("input[name=fname]").val(edit[0].fname);
    	    $("input[name=mname]").val(edit[0].mname);
    	    $("input[name=lname]").val(edit[0].lname);
    	    $("input[name=contact]").val(edit[0].contact);
    	    $("input[name=dob]").val(edit[0].dob);
    	    $("input[name=contact]").val(edit[0].contact);
    	    $('.sex').val(edit[0].sex);
    	    $('.civil_status').val(edit[0].civil_status);
            $("[name=nationality_id]").select2().select2('val', edit[0].nationality_id);
            $("input[name=occupation]").val(edit[0].occupation);
    	    $("input[name=address]").val(edit[0].address);
            $("input[name=passport_no]").val(edit[0].passport_no);
            $("input[name=house_no]").val(edit[0].house_no);
            $("input[name=street]").val(edit[0].street);
            $("#email").val(edit[0].email);
            $("#username").val(edit[0].username);
            existUsername = edit[0].username;
            $('input[name=password]').attr('required',false);
        }
        isCreate(id);

	}
    function isCreate(id) {
         if(id > 0) {
            $('#createAccount').addClass('hide');
        } else {
            $('#createAccount').removeClass('hide');
        }
    }
	$('#patient_modal').on('hidden.bs.modal', function () {
        $("#myModalLabel").html('Add Patient');
        if(user.level == 'admin') {
            $("#doctor_id").select2().select2('val', '');
        }
		$("#deleteBtn").addClass("hide");
		$('.select_phic').val('');
	    $("input[name=phic_id]").val('');
	    $("input[name=fname]").val('');
	    $("input[name=mname]").val('');
	    $("input[name=lname]").val('');
	    $("input[name=contact]").val('');
	    $("input[name=dob]").val('');
	    $("input[name=contact]").val('');
	    $('.sex').val('');
	    $('.civil_status').val('');
	    $("[name=muncity]").select2().select2('val', '');
	    $("[name=brgy]").select2().select2('val', '');
        $("input[name=occupation]").val('');
	    $("input[name=address]").val('');
        $('#createAccount').removeClass('hide');
         $("#email").val('');
        $("#username").val('');
        $("input[name=passport_no]").val('');
        $("input[name=house_no]").val('');
        $("input[name=street]").val('');
        existUsername = '';
        $('input[name=password]').attr('required',true);
	});

    $( ".generateUsername" ).click(function() {
        var name = $("input[name=email]").val().split('@');
        var text = name[0] + "_";
        var username = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 2; i++ )
            text += username.charAt(Math.floor(Math.random() * username.length));
        $("input[name=username]").val(text);
    });
    $( ".generatePassword" ).click(function() {
        var text = "";
        var password = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 8; i++ )
            text += password.charAt(Math.floor(Math.random() * password.length));
        $("input[name=password]").val(text);
    });

    $('#create_form').on('submit',function(e){
        e.preventDefault();
        if(processOne && processTwo) {
            $('.btnSaveAccount').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $('#create_form').ajaxSubmit({
                url:  "{{ url('/create-patient-account') }}",
                type: "POST",
                success: function(data){
                    setTimeout(function(){
                        window.location.reload(false);
                    },500);
                },
            });
        }
    });

    function getData(id, lname, mname, fname, contact) {
        $("input[name=account_id]").val(id);
        $("input[name=fname]").val(fname);
        $("input[name=mname]").val(mname);
        $("input[name=lname]").val(lname);
        $("input[name=contact]").val(contact);
    }

    function getUserData(id, lname, mname, fname, contact, email, username) {
        $("#myModalLabelAccount").html('Patient Account');
        $("input[name=account_id]").val(id);
        $("input[name=fname]").val(fname);
        $("input[name=mname]").val(mname);
        $("input[name=lname]").val(lname);
        $("input[name=contact]").val(contact);
        $("input[name=email]").val(email);
        $("input[name=username]").val(username);
        $('input[name=username]').attr('disabled',true);
        $('input[name=email]').attr('disabled',true);
        $('.rowPass').css('display', 'none');
        $('.rowFoot').css('display', 'none');
        $('.generateUsername').css('display', 'none');

    }
    $('#create_modal').on('hidden.bs.modal', function () {
        $("#myModalLabelAccount").html('Create Patient Account');
        $("input[name=account_id]").val('');
        $("input[name=fname]").val('');
        $("input[name=mname]").val('');
        $("input[name=lname]").val('');
        $("input[name=contact]").val('');
        $("input[name=email]").val('');
        $("input[name=username]").val('');
        $('input[name=username]').attr('disabled',false);
        $('input[name=email]').attr('disabled',false);
        $('.rowPass').css('display', 'block');
        $('.rowFoot').css('display', 'block');
        $('.generateUsername').css('display', 'block');
    });

    $( ".username" ).keyup(function() {
        invalidUsername = 0;
        $.each(users, function(key, value) {
            if(value.username == $("#username").val() || value.username == $("#username1").val()) {
                invalidUsername++;
            }
        });
        if(existUsername) {
            $(".username-has-error").addClass("hide");
            processOne = 'success';
        } else if(invalidUsername > 0) {
            $(".username-has-error").removeClass("hide");
            processOne = '';
        } else {
            $(".username-has-error").addClass("hide");
            processOne = 'success';
        }
    });

    $( ".email" ).keyup(function() {
        invalidEmail = 0;
        $.each(users, function(key, value) {
            if(value.email == $("#email").val() || value.email == $("#emailTwo").val()) {
                invalidEmail++;
            }
        });
        if(existUsername) {
            $(".username-has-error").addClass("hide");
            processOne = 'success';
        } else if(invalidUsername > 0) {
            $(".username-has-error").removeClass("hide");
            processOne = '';
        } else {
            $(".username-has-error").addClass("hide");
            processOne = 'success';
        }
    });
    $( "[name=dob]" ).change(function() {

        var text = $('[name=fname]').val();
        var username = $("[name=dob]").val().split('-');
        if(text.length > 0) {
            text += username[0] + username[1] + username[2];
            $("#usernamesug").html("suggestion:"+text);
        }
    });

    $(".reveal").on('click',function() {
        var $pwd = $(".pwd");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
            $(".reveal").html('<i class="far fa-eye-slash"></i>');
        } else {
            $pwd.attr('type', 'password');
            $(".reveal").html('<i class="far fa-eye"></i>');
        }
    });
    function accept(id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:  "{{ url('/patient-accept') }}/"+id,
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
    }

    function meetingInfo(id, isAccept) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:  "{{ url('/patient-consult-info') }}/"+id,
            type: "POST",
            success: function(data){
                if(data) {
                    var val = JSON.parse(data);
                    $('[name=patient_meeting_id]').val(id);
                    $('[name=meeting_info_id]').val(val.id);
                    $('[name=title]').val(val.title);
                    $('[name=datefrom]').val(val.datefrom);
                    $('[name=time]').val(val.time);
                    $('[name=duration]').val(val.duration);
                    $('[name=email]').val(val.email);
                    $("input[name=sendemail][value='"+val.sendemail+"']").prop("checked",true);
                    $('[name=meeting_id]').val(val.id);
                    if(isAccept > 0) {
                        $('#MeetingBody').addClass('disAble');
                        $('.btnSave').addClass('hide');
                        $('.btnCancel').addClass('hide');
                    } else {
                        validateTIme();
                        $('#MeetingBody').removeClass('disAble');
                        $('.btnSave').removeClass('hide');
                        $('.btnCancel').removeClass('hide');
                    }
                    $('#request_modal').modal('hide');
                    $('#meeting_info_modal').modal('show');
                }
            },
        });

    }
    function validateTIme() {
        var url = "{{ url('/validate-datetime') }}";
        var date = $("input[name=datefrom]").val();
        var time = $("input[name=time]").val();
        var doctor_id = $("select[name=doctor_id] option:checked").val();
        var duration = $("select[name=duration] option:checked").val();
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            data: {
                date: date,
                time: time,
                duration: duration
            },
            success : function(data){
                if(data > 0) {
                    Lobibox.notify('error', {
                        title: "Schedule",
                        msg: date + " " +time + " Schedule is not available! Please update Date and Time",
                        size: 'normal',
                        rounded: true
                    });
                    $("input[name=datefrom]").val('');
                    $("input[name=time]").val('');
                }
            }
        });
    }
    $('#consultation_form').on('submit',function(e){
        e.preventDefault();
        var id = $("input[name=meeting_info_id]").val();
        console.log(id)
        $('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Accepting...');
        $('#consultation_form').ajaxSubmit({
            url:  "{{ url('/patient-accept') }}/"+id,
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
        
    });

    function getUsername() {
        username = $("#usernamesug").html().split(':');
        $("#username").val(username[1]);
    }
    // $('#province').on('change', function() {
    //     var id = this.value;
    //     if(id) {
    //         $.ajax({
    //             url: "{{ url('facilities') }}/"+id+"/municipality",
    //             method: 'GET',
    //             success: function(result) {
    //                 $('#municipality').empty()
    //                 .append($('<option>', {
    //                     value: '',
    //                     text : 'Select Municipality...'
    //                 }));
    //                 $.each(result.municipal,function(key,value){
    //                     $('#municipality').append($("<option/>", {
    //                        value: value.muni_psgc,
    //                        text: value.muni_name
    //                     }));
    //                 });
    //             }
    //         });
    //     }
    // });
    //  $('#region').on('change', function() {
    //     var id = this.value;
    //     if(id) {
    //         $.ajax({
    //             url: "{{ url('facilities') }}/"+id+"/province",
    //             method: 'GET',
    //             success: function(result) {
    //                 $('#province').empty()
    //                 .append($('<option>', {
    //                     value: '',
    //                     text : 'Select Province...'
    //                 }));
    //                 $.each(result.province,function(key,value){
    //                     $('#province').append($("<option/>", {
    //                        value: value.prov_psgc,
    //                        text: value.prov_name
    //                     }));
    //                 });
    //             }
    //         });
    //     }
    // }); This will use in the future
</script>