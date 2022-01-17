<script>
    var patients = {!! json_encode($patients->toArray()) !!};
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
        var date = $("input[name=date_from]").val();
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
                    $("input[name=date_from]").val('');
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
        var url = "{{ url('/admin-patient-meeting-info') }}";
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
                $('[name=date_from]').val(val.datefrom);
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
        $('[name=date_from]').val('');
        $('[name=time]').val('');
        $('[name=duration]').val('');
        $('[name=email]').val('');
        $("input[name=sendemail][value='true']").prop("checked",true);
        $('[name=meeting_id]').val('');
        $('#saveBtn').removeClass('hide');
        $('#cancelBtn').removeClass('hide');
        $('#meetingInfo').removeClass('disAble');
    });

    function infoMeeting(id) {
        var url = "{{ url('/get-pending-meeting') }}";
        $.ajax({
            async: false,
            url: url+"/"+id,
            type: 'GET',
            success : function(data){
                console.log(data)
                var patient = data.patient.fname + ' ' + data.patient.mname + ' ' + data.patient.lname;
                var encoded = data.encoded.fname + ' ' + data.encoded.mname + ' ' + data.encoded.lname;
                var fac = data.encoded.facility.facilityname;
                var requestdate = moment(data.created_at).format('MMMM Do YYYY, h:mm:ss a');
                $('[name=req_meeting_id]').val(data.id);
                $('#txtEncoded').html(encoded);
                $('#req_fac').html('Facility: ' + fac);
                $('#txtreqDate').html(requestdate);
                $('[name=req_patient]').val(patient);
                $('[name=req_title]').val(data.title);
                $('[name=req_date]').val(moment(data.datefrom).format('MMMM D, YYYY'));
                $('[name=req_time]').val(data.time);
                $('[name=req_duration]').val(data.duration + ' Minutes');
                $('#tele_request_modal').modal('show');
            }
        });
    }

    $( ".btnSave" ).click(function() {
        var url = "{{ url('/accept-decline-meeting') }}";
        var action = $(this).attr("value");
        var id = $('[name=req_meeting_id]').val();
        var dateNow = new Date();
        var dateReq = new Date($('[name=req_date]').val());
        if(dateNow > dateReq) {
            Lobibox.notify('error', {
                title: "Schedule",
                msg: "Date of Teleconsultation is not valid anymore.",
                size: 'mini',
                rounded: true
            });
        } else {
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async: false,
                url: url+"/"+id,
                data: {
                    action: action 
                },
                type: 'POST',
                success : function(data){
                    setTimeout(function(){
                        window.location.reload(false);
                    },500);
                },
                error: function (data) {
                    var ht = action == 'Accept' ? '<i class="fas fa-check"></i> Accept' : '<i class="fas fa-times"></i> Decline';
                    $(this).html(ht);
                    Lobibox.notify('error', {
                        title: "Schedule",
                        msg: "Something went wrong, Please try again.",
                        size: 'normal',
                        rounded: true
                    });
                },
            });
        }
    });

    $( ".selectFacility" ).change(function() {
        var id = $(this).val();
        var url = "{{ url('/get-doctors-facility') }}";
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
            data: {
                fac_id: id
            },
            success : function(data){
                $('.selectDoctor').empty();
                var val = JSON.parse(data);
                $(".selectDoctor").append('<option selected>Select Doctor</option>').change();
                $.each(val,function(key,value){
                    $('.selectDoctor').append($("<option/>", {
                       value: value.id,
                       text: value.lname + ', ' + value.fname + ' ' + value.mname
                    }));
                });
                $('#scheduleMeeting').removeClass('hide');
            }
        });
    });

    $('#tele_form').on('submit',function(e){
        e.preventDefault();
        $('.btnSavePend').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#tele_form').ajaxSubmit({
            url:  "{{ url('/doctor-sched-pending') }}",
            type: "GET",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
                $('.btnSavePend').html('<i class="fas fa-check"></i> Save');
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'mini',
                    rounded: true
                });
            },
        });
    });

    $( "#patient_id" ).change(function() {
        var id = $(this).val();
        var email ='';
        const edit = [];
        $.each(patients, function(key, value) {
            if(value.id == id) {
                email = value.email
            }
        });
        $("input[name=email]").val(email);
    });
</script>