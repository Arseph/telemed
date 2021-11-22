<script>
	var patients = {!! json_encode($patients->toArray()) !!};
    var users = {!! json_encode($users->toArray()) !!};
	var toDelete;
    var invalidEmail;
    var invalidUsername;
    var processOne;
    var processTwo;
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
	});
    $('#patient_form').on('submit',function(e){
		e.preventDefault();
        $('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
		if(toDelete) {
			var id = $("#patient_id").val();
			$('#patient_form').ajaxSubmit({
	            url:  "{{ url('/patient-delete') }}/"+id,
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	        });
		} else {
			$('#patient_form').ajaxSubmit({
	            url:  "{{ url('/patient-store') }}",
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	        });
		}
	});
	function getDataFromData(ele) {
		$("#myModalLabel").html('Update Patient');
    	$("#patient_id").val($(ele).data('id'));
    	$("#deleteBtn").removeClass("hide");
    	const edit = [];
    	$.each(patients, function(key, value) {
	        if(value.id == $(ele).data('id')) {
	        	edit.push(value);
	        }
	    });
	    $('.select_phic').val(edit[0].phic_status).change();
	    $("input[name=phic_id]").val(edit[0].phic_id);
	    $("input[name=fname]").val(edit[0].fname);
	    $("input[name=mname]").val(edit[0].mname);
	    $("input[name=lname]").val(edit[0].lname);
	    $("input[name=contact]").val(edit[0].contact);
	    $("input[name=dob]").val(edit[0].dob);
	    $("input[name=contact]").val(edit[0].contact);
	    $('.sex').val(edit[0].sex);
	    $('.civil_status').val(edit[0].civil_status);
	    $("[name=muncity]").select2().select2('val', edit[0].muncity);
	    $("[name=brgy]").select2().select2('val', edit[0].brgy);
	    $("input[name=address]").val(edit[0].address);

	}
	$('#patient_modal').on('hidden.bs.modal', function () {
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
	    $("input[name=address]").val('');
	});

    $( ".generateUsername" ).click(function() {
        var text = "";
        var username = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 8; i++ )
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

    $( "#username" ).keyup(function() {
        invalidUsername = 0;
        $.each(users, function(key, value) {
            if(value.username == $("#username").val()) {
                invalidUsername++;
            }
        });
        if(invalidUsername > 0) {
            $(".username-has-error").removeClass("hide");
            processOne = '';
        } else {
            $(".username-has-error").addClass("hide");
            processOne = 'success';
        }
    });

    $( "#email" ).keyup(function() {
        invalidEmail = 0;
        $.each(users, function(key, value) {
            if(value.email == $("#email").val()) {
                invalidEmail++;
            }
        });
        if(invalidEmail > 0) {
            $(".email-has-error").removeClass("hide");
            processTwo = '';
        } else {
            $(".email-has-error").addClass("hide");
            processTwo = 'success';
        }
    });
</script>