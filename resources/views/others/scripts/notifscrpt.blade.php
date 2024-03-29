<script>
    <?php
    $user = Session::get('auth');
    $zoomclient = $user->zoom ? $user->zoom->zoom_client_id : '';
    ?>
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
	var patient_selected;
	var pusher = new Pusher('c7e7eb7e7b449b9f4230', {
      cluster: 'ap1'
    });
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', "{{ asset('public/sounds/notif.wav') }}");
    var activeid = "{{Session::get('auth')->id}}";
    var activefacility = "{{Session::get('auth')->facility_id}}";
    var level = "{{Session::get('auth')->level}}";
    var reqtel = pusher.subscribe('request-teleconsult');
    reqtel.bind('request-teleconsult-event', function(data) {
    if(activeid == data['to']) {
        audioElement.play();
        var html = '<div class="col-md-12" style="cursor: pointer; background: #2F4054; color: white;" onclick="goNotifTel('+data['id']+')">'+
            '<hr>'+
            '<b>('+data['facility']+')<br>'+data['from'] +'</b>' + ' Request a teleconsultation: "<code>' + data['title']+
            '</code>"<p style="color: red;">'+data['datereq']+'</p>'+
            '</div>';
        $("#contentCon").prepend(html);
        var total = parseInt($('#totalReq').html(), 10) + 1;
        var totalreq = parseInt($('#totReqTel').html(), 10) + 1;
        $('#totalReq').html(total);
        $('#totReqTel').html(totalreq);
        Lobibox.notify('success', {
            title: "Teleconsultation Request",
            msg: "From: " + data['facility'],
            size: 'large',
            rounded: true
        });
    }
    });
    var reqpat = pusher.subscribe('request-patient');
    reqpat.bind('request-patient-event', function(data) {
      if(activefacility == data['data']['facility_id'] && level == 'admin') {
        audioElement.play();
          var name = data['data']['lname']+', ' +data['data']['fname']+' ' +data['data']['mname'];
          var html = '<div class="col-md-12" style="cursor: pointer; background: #2F4054; color: white;" onclick="goNotifPat('+data['data']['id']+', '+data['account']['id']+')">'+
                '<hr>'+
                '<b>New Patient: "'+name+'"</b>' +
                '<p style="color: red;">'+data['data']['created_at']+'</p>'+
                '</div>';
          $("#contentPatAdmin").prepend(html);
          Lobibox.notify('success', {
                title: "New Patient",
                msg: name,
                size: 'large',
                rounded: true
            });
      }
    });
    var requestedtele = pusher.subscribe('requested-tele');
    requestedtele.bind('requested-tele-event', function(data) {
        if(activeid == data['data']['user_id']) {
        audioElement.play();
          var docname = data['user']['lname'] + ', '+ data['user']['fname']+' '+data['user']['mname'];
          var html = '<div class="col-md-12" style="cursor: pointer; background: #2F4054; color: white;" onclick="goRequested('+data['data']['id']+', '+data['data']['user_id']+')">'+
            '<hr>'+
            '<b>('+data['fac']+')<br>'+ docname +'</b>' + ' Accepted your teleconsultation: "<code>' + data['data']['title']+
            '</code>"<p style="color: red;">'+data['data']['created_at']+'</p>'+
            '</div>';
          var total = parseInt($('#totRequest').html(), 10) + 1;
          var totalacc = parseInt($('#totalReq').html(), 10) + 1;
            $('#totalReq').html(total);
            $('#totRequest').html('('+totalacc+')');
          $("#contentReq").prepend(html);
          Lobibox.notify('success', {
                title: "New accepted teleconsultation from " + docname,
                msg: data['data']['title'],
                size: 'large',
                rounded: true
            });
        }
    });
    var reqpatdoc = pusher.subscribe('doctor-request-patient');
    reqpatdoc.bind('doctor-request-patient-event', function(data) {
      if(activeid == data['data']['doctor_id']) {
        audioElement.play();
          var name = data['data']['lname']+', ' +data['data']['fname']+' ' +data['data']['mname'];
          var html = '<div class="col-md-12" style="cursor: pointer; background: #2F4054; color: white;" onclick="goNotifPat('+data['data']['id']+', '+data['account']['id']+')">'+
                '<hr>'+
                '<b>New Patient: "'+name+'"</b>' +
                '<p style="color: red;">'+data['data']['created_at']+'</p>'+
                '</div>';
          var total = parseInt($('#totalReq').html(), 10) + 1;
          var totalreq = parseInt($('#totReqPat').html(), 10) + 1;
          $('#totalReq').html(total);
            $('#totReqPat').html(totalreq);
          $("#contentPat").prepend(html);
          Lobibox.notify('success', {
                title: "New Patient",
                msg: name,
                size: 'large',
                rounded: true
            });
      }
    });
    $(document).ready(function() {
        $(".select2").select2();
        if("{{Session::get('auth')->level}}" == 'doctor') {
            var url = "{{ url('/fetch-notification') }}";
            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function(data){
                    $('#totalReq').html(data['totalreq']);
                    $('#totReqTel').html(data['totalmeet']);
                    $('#totReqPat').html(data['totalpat']);
                    $('#totRequest').html();
                    $(data['reqmeet']).each(function(i) {
                      var html = '<div class="col-md-12" style="cursor: pointer;")"><a href=" {{url("/teleconsultation")}}"'+
                            '<hr>'+
                            '<b>('+data['reqmeet'][i]['facname']+')<br>'+data['reqmeet'][i]['fromLname'] + ', '+data['reqmeet'][i]['fromFname']+' '+data['reqmeet'][i]['fromMname'] +'</b>' + ' Request a teleconsultation: "<code>' + data['reqmeet'][i]['title']+
                            '</code>"<p style="color: red;">'+data['reqmeet'][i]['datereq']+'</p>'+
                            '<a</div>';
                      $("#contentCon").append(html);
                    });
                    $(data['reqpatient']).each(function(i) {
                      var mname = data['reqpatient'][i]['mname'] ? data['reqpatient'][i]['mname'] : '';
                      var name = data['reqpatient'][i]['lname']+', ' +data['reqpatient'][i]['fname']+' ' + mname;
                      var html = '<div class="col-md-12" style="cursor: pointer;" onclick="goNotifPat('+data['reqpatient'][i]['id']+')">'+
                        '<hr>'+
                        '<b>New Patient: "'+name+'"</b>' +
                        '<p style="color: red;">'+data['reqpatient'][i]['created_at']+'</p>'+
                        '</div>';
                      $("#contentPat").append(html);
                    });
                    $(data['requested']).each(function(i) {
                        var docname = data['requested'][i]['doctor']['lname'] + ', '+ data['requested'][i]['doctor']['fname']+' '+data['requested'][i]['doctor']['mname'];
                        var html = '<div class="col-md-12" style="cursor: pointer;" onclick="goRequested('+data['requested'][i]['id']+', '+data['requested'][i]['user_id']+')">'+
                            '<hr>'+
                            '<b>('+data['requested'][i]['doctor']['facility']['facilityname']+')<br>'+ docname +'</b>' + ' Accepted your teleconsultation: "<code>' + data['requested'][i]['title']+
                            '</code>"<p style="color: red;">'+data['requested'][i]['created_at']+'</p>'+
                            '</div>';
                        $("#contentReq").append(html);
                    });
                },
                error : function(data){
                    Lobibox.notify('error', {
                        title: "",
                        msg: "Something Went Wrong while fetching notification. Please Refresh the Page.",
                        size: 'mini',
                        rounded: true
                    });
                }
            });
        }
        if("{{Session::get('auth')->level}}" == 'admin') {
            var url = "{{ url('/fetch-notification') }}";
            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function(data){
                    $('#totalReqAdmin').html(data['totalpat']);
                    $(data['reqpatient']).each(function(i) {
                      var mname = data['reqpatient'][i]['mname'] ? data['reqpatient'][i]['mname'] : '';
                      var name = data['reqpatient'][i]['lname']+', ' +data['reqpatient'][i]['fname']+' ' + mname;
                      var html = '<div class="col-md-12" style="cursor: pointer;" onclick="goNotifPat('+data['reqpatient'][i]['id']+')">'+
                        '<hr>'+
                        '<b>New Patient: "'+name+'"</b>' +
                        '<p style="color: red;">'+data['reqpatient'][i]['created_at']+'</p>'+
                        '</div>';
                      $("#contentPatAdmin").append(html);
                    });
                },
                error : function(data){
                    Lobibox.notify('error', {
                        title: "",
                        msg: "Something Went Wrong while fetching notification. Please Refresh the Page.",
                        size: 'mini',
                        rounded: true
                    });
                }
            });
        }
    });
    $('.chip').on('click', function (event) {
        $('.chip').removeClass('actColor');
        $('#'+this.id).addClass('actColor');
        switch(this.id) {
          case 'chipCon':
            $('#contentCon').removeClass('hide');
            $('#contentPat').addClass('hide');
            $('#contentReq').addClass('hide');
            break;
          case 'chipPat':
            $('#contentCon').addClass('hide');
            $('#contentPat').removeClass('hide');
            $('#contentReq').addClass('hide');
            break;
          case 'chipReq':
            $('#contentCon').addClass('hide');
            $('#contentPat').addClass('hide');
            $('#contentReq').removeClass('hide');
            break;
        }
    });
    function goNotifTel(id) {
        audioElement.play();
        var url = "{{ url('/get-pending-meeting') }}";
        $.ajax({
            async: false,
            url: url+"/"+id,
            type: 'GET',
            success : function(data){
                var mname = data.mname ? data.mname : '';
                var patient = data.patient.fname + ' ' + mname + ' ' + data.patient.lname;
                var encoded = data.encoded.fname + ' ' + data.encoded.mname + ' ' + data.encoded.lname;
                var fac = data.encoded.facility.facilityname;
                var requestdate = moment(data.created_at).format('MMMM Do YYYY, h:mm:ss a');
                $('#notif_meeting_id').val(data.id);
                $('#notiftxtEncoded').html(encoded);
                $('#notif_fac').html('Facility: ' + fac);
                $('#notifreqDate').html(requestdate);
                $('#notif_patient').val(patient);
                $('#notif_title').val(data.title);
                $('#notif_tele_request_modal').modal('show');
               
            }
        });
    }
    function goNotifPat(id) {
    	patient_selected = id;
        var url = '{{url("/notif-patient-info")}}';
        $.ajax({
            url: url+"/"+id,
            type: 'GET',
            success: function(data) {
            	$('#patientNotif').html(data);
            	$('#req_patient_modal').modal('show');
            }
        });
    }
    $('.btnNotifAccept').on('click', function (event) {
    	$(".loading").show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:  "{{ url('/notif-patient-accept') }}/"+patient_selected,
            type: "POST",
            data: {
                docid: $('#doctorIDnotif').val()
            },
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
    $('#doctorIDnotif').on('change', function (event) {
        if(this.value) {
            $('.btnNotifAccept').prop('disabled', false);
        } else {
            $('.btnNotifAccept').prop('disabled', true);
        }
    });
    var active = "{{Session::get('auth')->level}}";
    // if(active == 'doctor') {
    //     var last_update = "<?php 
    //         $token = \App\ZoomToken::where('facility_id',$user->facility_id)->first() ?
    //                             \App\ZoomToken::where('facility_id',$user->facility_id)->first()->updated_at
    //                             : 'none';
    //         echo $token;
    //         ?>";
    //     var zoomtoken = last_update == 'none' ? '' : new Date(last_update);
    //     var expirewill = last_update != 'none' ? zoomtoken.setHours(zoomtoken.getHours() + 1) : '';
    //     $('.countdowntoken').countdown(expirewill, function(event) {
    //         if(event.strftime('%H:%M:%S') == '00:00:00') {
    //             if(last_update == 'none') {
    //                 $(this).html('You don\'t have access token. Please contact administrator.');
    //                 $('#notifacceptBtn').prop("disabled", true);
    //                 $('#accnotifacceptBtn').prop("disabled", true);
    //             } else {
    //               $(this).html('Access token was expired.');
    //               $('.refTok').html('Get your token here');
    //               var tokurl;
    //                var cred = "{{$zoomclient}}";
    //                if(cred) {
    //                 tokurl = "https://zoom.us/oauth/authorize?response_type=code&client_id="+cred+"&redirect_uri={{env('ZOOM_REDIRECT_URL')}}";
    //                 $(".refTok").attr("href", tokurl);
    //                }
    //                 $('#notifacceptBtn').prop("disabled", true);
    //                 $('#accnotifacceptBtn').prop("disabled", true);
    //             }
    //         } else {
    //             $(this).html('Zoom Token validation left: '+ event.strftime('%M:%S'));
    //             $('#notifacceptBtn').prop("disabled", false);
    //             $('#accnotifacceptBtn').prop("disabled", false);
    //         }
    //     });
    //     function refreshToken() {
    //         var url = "{{ url('/refresh-token') }}";
    //         $.ajax({
    //             url: url,
    //             type: 'GET',
    //             async: false,
    //             success : function(data){
    //                 var val = JSON.parse(data);
    //                 if(val.updated_at != last_update) {
    //                     last_update = val.updated_at;
    //                     zoomtoken = new Date(last_update);
    //                     expirewill = zoomtoken.setHours(zoomtoken.getHours() + 1);
    //                     $('.countdowntoken').countdown(expirewill, function(event) {
    //                         if(event.strftime('%H:%M:%S') == '00:00:00') {
    //                           $(this).html('Your access token is expired.');
    //                           $('.refTok').html('Refresh your token here');
    //                           $('#notifacceptBtn').prop("disabled", true);
    //                           $('#accnotifacceptBtn').prop("disabled", true);
    //                         } else {
    //                             $(this).html('Your access token will expire in '+ event.strftime('%H:%M:%S'));
    //                             $('#notifacceptBtn').prop("disabled", false);
    //                             $('#accnotifacceptBtn').prop("disabled", false);
    //                         }
    //                     });
    //                     clearInterval(interval);
    //                 }
    //             }
    //         });
    //     }
    //     $('.refTok').on('click',function () {
    //         interval = setInterval(refreshToken, 5000);
    //     });
    // }
    var notifcalendarFac = document.getElementById('notif-fac-calendar');
    var notiffaccalendar = new FullCalendar.Calendar(notifcalendarFac, {
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth'
      },
      initialDate: moment(new Date).format(),
      navLinks: true,
      editable: true,
      dayMaxEvents: true,
      events: {
        url: "{{ url('/calendar-meetings') }}",
        failure: function() {
          alert('error getting teleconsultations')
        }
      },
    });
    $('#notif_calendar_meetings_modal').on('shown.bs.modal', function () {
        notiffaccalendar.render();
    });
    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
      notifvalidateTIme();
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.daterange').daterangepicker({
        minDate: today,
        "drops": "up",
        "singleDatePicker": true
    });
    function notifvalidateTIme() {
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
                if(data == 'Not valid') {
                    Lobibox.notify('error', {
                        title: "Schedule",
                        msg: "Please set a schedule before 3 hours of Teleconsultation",
                        size: 'normal',
                        rounded: true
                    });
                    $("input[name=time]").val('');
                }
                else if(data > 0) {
                    Lobibox.notify('error', {
                        title: "Schedule",
                        msg: "Schedule is not available!",
                        size: 'normal',
                        rounded: true
                    });
                    $("input[name=time]").val('');
                }
            }
        });
    }
    var action;
    $( ".btnSave" ).click(function() {
        action = $(this).attr("value");
    });
    $('#notif_accept_decline_form').on('submit',function(e){
        var url = "{{ url('/accept-decline-meeting') }}";
        var id = $('#notif_meeting_id').val();
        e.preventDefault();
        $(".loading").show();
        $('#notif_accept_decline_form').ajaxSubmit({
            url:  url+"/"+id,
            type: "POST",
            data: {
                action: action
            },
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
                $('.btnSave').html('<i class="fas fa-check"></i> Save');
                $(".loading").hide();
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'mini',
                    rounded: true
                });
            },
        });
    });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
        twelvehour: true,
        placement: 'top',
        align: 'left',
        afterDone: function() {
            notifvalidateTIme();
        }
    });

    function goRequested(id, user_id) {
        var urlmet = "{{ url('/meeting-info') }}";
        $.ajax({
            async: true,
            url: urlmet,
            type: 'GET',
            data: {
                meet_id: id
            },
            success : function(data){
                var val = JSON.parse(data);
                var today = moment(new Date());
                let diff = today.diff(moment(val['date_meeting']), 'days');
                if(val) {
                    var time = moment(val['date_meeting']).format('MMMM D, YYYY')+' '+moment(val['from_time'], "HH:mm:ss").format('h:mm A')+' - '+moment(val['to_time'], "HH:mm:ss").format('h:mm A');
                    var mname = val['mname'] ? val['mname'] : ''
                    $('#reqtimeConsult').html(time);
                    $('#reqmyInfoLabel').html(val['title']);
                    $('#reqmeetlink').html(val['web_link']);
                    $('#reqmeetnumber').html(val['meeting_id']);
                    $('#reqpatientName').val(val['lname']+", "+val['fname']+" "+mname);
                    $('#reqmeetPass').html(val['password']);
                    $('#reqmeetKey').html(val['host_key']);
                    $('.btnMeeting').val(val['meetID']);
                    $('#request_info_meeting_modal').modal('show'); 
                    if(diff >= 0) {
                         $('.btnMeeting').prop('disabled', true);
                         $('.btnMeeting').html('Consultation complete');
                    } else if(user_id == activeid) {
                        $('.btnMeeting').prop('disabled', false);
                        $('.btnMeeting').html('<i class="fas fa-play-circle"></i> Join Consultation');
                    } else {
                        $('.btnMeeting').prop('disabled', false);
                        $('.btnMeeting').html('<i class="fas fa-play-circle"></i> Start Consultation');
                    }
                }
            }
        });
    }

    $( ".proceedNotifBtn" ).click(function() {
        $('#accept_notif_tele_request_modal').modal('show');
    });

    $('#accept_notif_accept_decline_form').on('submit',function(e){
        var url = "{{ url('/accept-notif-meeting') }}";
        e.preventDefault();
        $(".loading").show();
        $('#accept_notif_accept_decline_form').ajaxSubmit({
            url:  url,
            type: "POST",
            data: {
                action: action,
                patient_id: patient_selected
            },
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function (data) {
                $('.btnSave').html('<i class="fas fa-check"></i> Save');
                $(".loading").hide();
                Lobibox.notify('error', {
                    title: "Schedule",
                    msg: "Something went wrong, Please try again.",
                    size: 'mini',
                    rounded: true
                });
            },
        });
    });

</script>