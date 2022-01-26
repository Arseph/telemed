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
        $('.daterange').daterangepicker({
            "singleDatePicker": true
        });
        $('input[type=radio][name=known_covid_case]').change();
    });
	function enableView() {
		$('#formEdit').removeClass('disAble');
		$( 'textarea[name="reason_consult"]' ).focus();
		$( '.btnSave' ).removeClass('hide');
		$( '#btnEdit' ).addClass('hide');
		$( '.btnAddrow' ).removeClass('hide');
	}
	$('#clinical_form').on('submit',function(e){
		e.preventDefault();
		$(".loading").show();
		$('#clinical_form').ajaxSubmit({
			url:  "{{ url('/admin/clinical-store') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
            	$(".loading").hide();
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'normal',
                    rounded: true
                });
            },
		});

	});
	$('#covid_form').on('submit',function(e){
		e.preventDefault();
		var values = $("input[name='list_name_occasion[]']")
              .map(function(){return $(this).val();}).get();
		$(".loading").show();
		$('#covid_form').ajaxSubmit({
			url:  "{{ url('/admin/covid-store') }}",
            type: "POST",
            data: {
            	list_name_occa: values
            },
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
            	$(".loading").hide();
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'normal',
                    rounded: true
                });
            },
		});

	});
	$( window ).scroll(function() {
	  if($(window).scrollTop() > 250) {
	  	$( '.btnSave' ).addClass('btnSaveMove');
	  } else {
	  	$( '.btnSave' ).removeClass('btnSaveMove');
	  }
	});
	$('input[type=radio][name=known_covid_case]').change(function() {
	    if ($("input[name='known_covid_case']:checked").val() == 1) {
	        $('input[name="date_contact_known_covid_case"]').prop("disabled", false);
	    }
	    else {
	    	$('input[name="date_contact_known_covid_case"]').val('');
	        $('input[name="date_contact_known_covid_case"]').prop("disabled", true);
	    }
	});
	$(".btnAddrow").click(function () {
        var html = '';
        html += '<div class="inputRow input-group">';
        html += '<input type="text" name="list_name_occasion[]" class="form-control" placeholder="e.g John Doe - 1234567890">';
        html += '<div class="input-group-btn">';
        html += '<button class="btnRemoveRow btn btn-danger" type="button">Remove</button>';
        html += '</div>';
        html += '</div>';
        html += '<br>';

        $('#nameContact').append(html);
    });
     $(document).on('click', '.btnRemoveRow', function () {
        $(this).closest('.inputRow').remove();
    });
</script>