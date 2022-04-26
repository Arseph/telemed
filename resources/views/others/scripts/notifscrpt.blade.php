<script>
	var patient_selected;
	var pusher = new Pusher('456a1ac12f0bec491f4c', {
      cluster: '{{env("PUSHER_APP_CLUSTER")}}'
    });

    var activeid = "{{Session::get('auth')->id}}";
    var reqtel = pusher.subscribe('request-teleconsult');
    reqtel.bind('request-teleconsult-event', function(data) {
    if(activeid == data['to']) {
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
            size: 'mini',
            rounded: true
        });
    }
    });
    var reqpat = pusher.subscribe('request-patient');
    reqpat.bind('request-patient-event', function(data) {
      console.log(data['account']['id']);
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
            size: 'mini',
            rounded: true
        });
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
                    console.log(data)
                    $('#totalReq').html(data['totalreq']);
                    $('#totReqTel').html(data['totalmeet']);
                    $('#totReqPat').html(data['totalpat']);
                    $('#totRequest').html();
                    $(data['reqmeet']).each(function(i) {
                      var html = '<div class="col-md-12" style="cursor: pointer;" onclick="goNotifTel('+data['reqmeet'][i]['pendID']+')">'+
                            '<hr>'+
                            '<b>('+data['reqmeet'][i]['facname']+')<br>'+data['reqmeet'][i]['fromLname'] + ', '+data['reqmeet'][i]['fromFname']+' '+data['reqmeet'][i]['fromMname'] +'</b>' + ' Request a teleconsultation: "<code>' + data['reqmeet'][i]['title']+
                            '</code>"<p style="color: red;">'+data['reqmeet'][i]['datereq']+'</p>'+
                            '</div>';
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
        console.log(id)
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
</script>