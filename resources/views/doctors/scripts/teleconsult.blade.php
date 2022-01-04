<script>
	$(document).ready(function() {
		var date = new Date();
		var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('#consolidate_date_range').daterangepicker({
            minDate: today
        });
        $('#consolidate_date_range_past').daterangepicker({
            maxDate: today
        });
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
    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
      validateTIme();
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
                duration: duration,
                doctor_id: doctor_id
            },
            success : function(data){
                if(data > 0) {
                    Lobibox.notify('error', {
                        title: "Schedule",
                        msg: "Schedule is not available!",
                        size: 'normal',
                        rounded: true
                    });
                    $("input[name=datefrom]").val('');
                    $("input[name=time]").val('');
                }
            }
        });
    }
	$('.select_phic').on('change',function(){
        var status = $(this).val();
        if(status!='none'){
            $('.phicID').attr('disabled',false);
        }else{
            $('.phicID').val('').attr('disabled',true);
        }
    });
    $('#meeting_form').on('submit',function(e){
		e.preventDefault();
        $('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
		$('#meeting_form').ajaxSubmit({
            url:  "{{ url('/add-meeting') }}",
            type: "GET",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
                $('.btnSave').html('<i class="fas fa-check"></i> Save');
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'normal',
                    rounded: true
                });
            },
        });
	});

    function getMeeting(id) {
        var url = "{{ url('/meeting-info') }}";
        var tmp;
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
            data: {
                meet_id: id
            },
            success : function(data){
                var val = JSON.parse(data);
                $('#info_meeting_modal').modal('show'); 
                $('#myInfoLabel').html(val['title']);
                $('#meetlink').html(val['web_link']);
                $('#meetnumber').html(val['meeting_number']);
                $('#patientName').val(val['lname']+", "+val['fname']+" "+val['mname']);
                $('#meetPass').html(val['password']);
                $('#meetKey').html(val['host_key']);
                $('.btnMeeting').val(val['meetID']);
            }
        });
    }

    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
      Lobibox.notify('success', {
            title: "",
            msg: "copy to clipboard success",
            size: 'mini',
            rounded: true
        });
    }

    function startMeeting(id) {
        var url = "{{ url('/start-meeting') }}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            url: url+"/"+id,
            type: 'GET',
            success : function(data){
                window.open(url+"/"+id,'_blank')
            }
        });
    }

    $( ".btnMeeting" ).click(function() {
        startMeeting($(this).attr("value"));
    });

    function getEmail(email, fname, mname, lname, id) {
        $('#myModalMeetingLabel').html('Schedule Teleconsultation for ' + fname +' ' + mname + ' ' + lname);
        $('input[name=email]').val(email);
        $('input[name=patient_id]').val(id);

    }

    $('#schedule_form').on('submit',function(e){
        e.preventDefault();
        $('.btnSave').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#schedule_form').ajaxSubmit({
            url:  "{{ url('/admin-sched-pending') }}",
            type: "GET",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
                $('.btnSave').html('<i class="fas fa-check"></i> Save');
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'normal',
                    rounded: true
                });
            },
        });
    });

    $('.selectDoctor').on('change',function(){
        var status = $(this).val();
        if(status > 0){
            $('#scheduleMeeting').removeClass('hide');
        }else{
            $('#scheduleMeeting').addClass('hide');
        }
    });

    function getSchedule(id, fname, mname, lname) {
        $('#myModalMeetingLabel').html('Update Schedule Teleconsultation for ' + fname +' ' + mname + ' ' + lname);
        var url = "{{ url('/admin-meeting-info') }}";
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
            data: {
                meet_id: id
            },
            success : function(data){
                var val = JSON.parse(data);
                console.log(val)
                $("[name=doctor_id]").select2().select2('val', val.doctor_id);
                $('[name=patient_id]').val(val.patient_id);
                $('[name=title]').val(val.title);
                $('[name=datefrom]').val(val.datefrom);
                $('[name=time]').val(val.time);
                $('[name=duration]').val(val.duration);
                $('[name=email]').val(val.email);
                $("input[name=sendemail][value='"+val.sendemail+"']").prop("checked",true);
                $('[name=meeting_id]').val(val.id);
                if(val.meet_id) {
                    $('#saveBtn').addClass('hide');
                    $('#cancelBtn').addClass('hide');
                    $('#meetingInfo').addClass('disAble');
                }
            }
        });
    }

    $('#meeting_modal').on('hidden.bs.modal', function () {
        $("[name=doctor_id]").select2().select2('val', '');
        $('[name=patient_id]').val('');
        $('[name=title]').val('');
        $('[name=datefrom]').val('');
        $('[name=time]').val('');
        $('[name=duration]').val('');
        $('[name=email]').val('');
        $("input[name=sendemail][value='true']").prop("checked",true);
        $('[name=meeting_id]').val('');
        $('#saveBtn').removeClass('hide');
        $('#cancelBtn').removeClass('hide');
        $('#meetingInfo').removeClass('disAble');
    });
</script>