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
    });
	function enableView() {
		$('#formEdit').removeClass('disAble');
		$( 'textarea[name="reason_consult"]' ).focus();
		$( '.btnSave' ).removeClass('hide');
		$( '#btnEdit' ).addClass('hide');
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
	$( window ).scroll(function() {
	  if($(window).scrollTop() > 250) {
	  	$( '.btnSave' ).addClass('btnSaveMove');
	  } else {
	  	$( '.btnSave' ).removeClass('btnSaveMove');
	  }
	});
</script>