<script>
	var prescription = {!! json_encode($prescription->toArray()) !!};
	console.log(prescription)
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
    $('#prescription_modal').on('submit',function(e){
		e.preventDefault();
        $(".loading").show();
		$('#prescription_modal').ajaxSubmit({
            url:  "{{ url('/prescription-store') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error : function(data){
                $(".loading").hide();
                Lobibox.notify('error', {
                    title: "",
                    msg: "Something Went Wrong. Please Try again.",
                    size: 'mini',
                    rounded: true
                });
            }
        });
	});

	function getData(id) {
		$("#myModalLabel").html('Update Prescription');
		$("#id").val(id);
		const edit = [];
    	$.each(prescription, function(key, value) {
	        if(value.id == id) {
	        	edit.push(value);
	        }
	    });
	    if(edit.length > 0 ) {
	    	$("input[name=presc_code]").val(edit[0].presc_code);
	    	$('#type_of_medicine').val(edit[0].type_of_medicine).change();
	    	$('#drug_id').val(edit[0].drug_id).change();
	    	$('#frequency').val(edit[0].frequency).change();
	    	$('#dose_regimen').val(edit[0].dose_regimen).change();
	    	$("input[name=total_qty]").val(edit[0].total_qty);
			$("input[name=medication]").val(edit[0].medication);
			$('#prescribebyid').val(edit[0].prescribebyid).change();
	    }
	}
</script>