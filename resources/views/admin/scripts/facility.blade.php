<script>;
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
	function enableView() {
		$('#formEdit').removeClass('disAble');
		$( 'input[name="facilityname"]' ).focus();
		$( '.btnSave' ).removeClass('hide');
		$( '#btnEdit' ).addClass('hide');
	}

	$('#facility_form').on('submit',function(e){
		e.preventDefault();
		$('#facility_form').ajaxSubmit({
            url:  "{{ url('/update-facility') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
	});
</script>