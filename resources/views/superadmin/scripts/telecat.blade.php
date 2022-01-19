<script>
	var telecat = {!! json_encode($telecat->toArray()) !!};
	var toDelete;
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
	$( "#deleteBtn" ).click(function() {
		toDelete = 'yes';
	});
	$('#tele_form').on('submit',function(e){
		e.preventDefault();
		$('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
		var id = $("#telecat_id").val();
		if(toDelete) {
			$('#tele_form').ajaxSubmit({
	            url:  "{{ url('/telecat-delete') }}/"+id,
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	        });
		} else {
			$('#tele_form').ajaxSubmit({
	            url:  "{{ url('/telecat-store') }}",
	            type: "POST",
	            success: function(data){
	                setTimeout(function(){
	                    window.location.reload(false);
	                },500);
	            },
	            error: function(xhr, status, error) {
				  Lobibox.notify('error', {
			            title: "",
			            msg: "Something went wrong. Please Try again.",
			            size: 'mini',
			            rounded: true
			        });
				  $('.btnSave').html('<i class="fas fa-check"></i> Save');
				}
	        });
		}
	});

	function getData(ele) {
		$("#myModalLabel").html('Update Tele Category');
    	$("#telecat_id").val($(ele).data('id'));
    	const edit = [];
    	$.each(telecat, function(key, value) {
	        if(value.id == $(ele).data('id')) {
	        	edit.push(value);
	        }
	    });

	    $("input[name=category_name]").val(edit[0].category_name);
	    $("#deleteBtn").removeClass("hide");
	}
	$('#province_modal').on('hidden.bs.modal', function () {
		$("#myModalLabel").html('Add Tele Category');
		$("input[name=category_name]").val('');
	    $("#telecat_id").val('');
	    $("#deleteBtn").addClass("hide");
	});
</script>