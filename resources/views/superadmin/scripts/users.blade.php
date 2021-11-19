<script>    
    var users = {!! json_encode($users->toArray()) !!};
    var processOne;
    var processTwo;
    var invalid;
    var Deactivate;
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
    @if(Session::get('deactivate'))
        Lobibox.notify('error', {
            title: "",
            msg: "<?php echo Session::get('deactivate'); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
            Session::put("deactivate",false);
        ?>
    @endif
    $( "#username" ).keyup(function() {
	    invalid = 0;
    	$.each(users, function(key, value) {
	        if(value.username == $("#username").val()) {
	        	invalid++;
	        }
	    });
	    if(invalid > 0) {
	    	$(".username-has-error").removeClass("hide");
		    $('.btnSave').prop('disabled', true);
		    processOne = '';
	    } else {
	    	$(".username-has-error").addClass("hide");
	    	$('.btnSave').prop('disabled', false);
	    	$('#username').css("border","");
	    	processOne = 'success';
	    }
	});
	$( '#deactBtn, #actBtn' ).click(function() {
		Deactivate = 'yes';
		$( "#user_form" ).submit();
	});
	$('#password2').keyup(function() {
		if($(this).val() != $('#password1').val()) {
			$(".password-has-error").removeClass("hide");
			$(".password-has-match").addClass("hide");
			$('.btnSave').prop('disabled', true);
			processTwo = '';
		} else {
			$(".password-has-error").addClass("hide");
			$(".password-has-match").removeClass("hide");
			$('.btnSave').prop('disabled', false);
			$('#password2').css("border","");
			processTwo = 'success';
		}
	});
	$("#container").removeClass("container");
    $("#container").addClass("container-fluid");
    $('#user_form').on('submit',function(e){
		e.preventDefault();
		var id = $("#user_id").val();
		if(Deactivate) {
			$('#user_form').ajaxSubmit({
	            url:  "{{ url('/user-deactivate') }}/"+id,
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	        });
		}
		if(!$('.username-has-error').hasClass("hide")) {
			$("#username").focus();
			$('#username').css("border","red solid 3px");
		} else if (!$('.password-has-error').hasClass("hide")) {
			$("#password2").focus();
			$('#password2').css("border","red solid 3px");
		} else if(!$('.username-has-error').hasClass("hide") &&
				!$('.password-has-error').hasClass("hide")) {
			$('#username').css("border","red solid 3px");
			$('#password2').css("border","red solid 3px");
		}
		else if(processOne && processTwo && invalid == 0) {
			$('#user_form').ajaxSubmit({
	            url:  "{{ url('/user-store') }}",
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	        });
		}
	});
	function getDataFromUser(el) {
		$("#myModalLabel").html('Update User');
		$("#user_id").val($(el).data('id'));
		const edit = [];
    	$.each(users, function(key, value) {
	        if(value.id == $(el).data('id')) {
	        	edit.push(value);
	        }
	    });
	    if(edit[0].status=='active') {
	    	$("#deactBtn").removeClass("hide")
	    } else  {
			$("#actBtn").removeClass("hide");
	    }
	    $("input[name=fname]").val(edit[0].fname);
	    $("input[name=mname]").val(edit[0].mname);
	    $("input[name=lname]").val(edit[0].lname);
	    $("input[name=contact]").val(edit[0].contact);
	    $("input[name=email]").val(edit[0].email);
	    $("[name=facility_id]").select2().select2('val', edit[0].facility_id);
	    $("input[name=designation]").val(edit[0].designation);
	    $("[name=level]").select2().select2('val', edit[0].level);
	    $("input[name=username]").val(edit[0].username);

	}

	$('#users_modal').on('hidden.bs.modal', function () {
		$("#myModalLabel").html('Add User');
		$("#user_id").val('');
		$("input[name=fname]").val('');
	    $("input[name=mname]").val('');
	    $("input[name=lname]").val('');
	    $("input[name=contact]").val('');
	    $("input[name=email]").val('');
	    $("[name=facility_id]").select2().select2('val', '');
	    $("input[name=designation]").val('');
	    $("[name=level]").select2().select2('val', '');
	    $("input[name=username]").val('');
	    $("#deactBtn").addClass("hide");
	})

</script>