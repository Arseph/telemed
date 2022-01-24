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
	function enableView() {
		$('#formEdit').removeClass('disAble');
		$( 'input[name="facilityname"]' ).focus();
		$( '.btnSave' ).removeClass('hide');
		$( '#btnEdit' ).addClass('hide');
	}
</script>