<script>
    var pusher = new Pusher('456a1ac12f0bec491f4c', {
      cluster: '{{env("PUSHER_APP_CLUSTER")}}'
    });

    var reqtel = pusher.subscribe('request-teleconsult');
    reqtel.bind('request-teleconsult-event', function(data) {
    var id = "{{Session::get('auth')->id}}";
    if(id == data['to']) {
        var html = '<div class="col-md-12" style="cursor: pointer; background: #2F4054; color: white;" onclick="goNotif('+data['id']+')">'+
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
      alert(JSON.stringify(data));
    });
    var loc = '';
    $('.searchPat').on('keyup',function(){
        var searchTerm = $(this).val().toLowerCase();
        $('#patientTable tbody tr').each(function(){
            var lineStr = $(this).text().toLowerCase();
            if(lineStr.indexOf(searchTerm) === -1){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    });

    function seturl(set) {
        loc = set
    }
    function gourl(id) {
        var url = "{{ url('patient') }}"+"/"+loc+"/"+id;
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            success: function(data){
                window.location.href = url;
            },
            error : function(data){
                Lobibox.notify('error', {
                    title: "",
                    msg: "Something Went Wrong. Please Try again.",
                    size: 'mini',
                    rounded: true
                });
            }
        });
    }
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
                      var html = '<div class="col-md-12" style="cursor: pointer;" onclick="goNotif('+data['reqmeet'][i]['pendID']+')">'+
                            '<hr>'+
                            '<b>('+data['reqmeet'][i]['facname']+')<br>'+data['reqmeet'][i]['fromLname'] + ', '+data['reqmeet'][i]['fromFname']+' '+data['reqmeet'][i]['fromMname'] +'</b>' + ' Request a teleconsultation: "<code>' + data['reqmeet'][i]['title']+
                            '</code>"<p style="color: red;">'+data['reqmeet'][i]['datereq']+'</p>'+
                            '</div>';
                      $("#contentCon").append(html);
                    });
                },
                error : function(data){
                    Lobibox.notify('error', {
                        title: "",
                        msg: "Something Went Wrong while fetching notification.",
                        size: 'mini',
                        rounded: true
                    });
                }
            });
        }
    });
    function refreshPage(){
        <?php
            use Illuminate\Support\Facades\Route;
            $current_route = Route::getFacadeRoot()->current()->uri();
        ?>
        $('.loading').show();
        window.location.replace("<?php echo asset($current_route) ?>");
    }

    function loadPage(){
        $('.loading').show();
    }
    //Get the button
    var mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        $('body,html').animate({
            scrollTop : 0 // Scroll to top of body
        }, 500);
    }

    $('#webex_form').on('submit',function(e){
        e.preventDefault();
        $('.btnSaveWebex').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#webex_form').ajaxSubmit({
            url:  "{{ url('/webex-token') }}",
            type: "POST",
            success: function(data){
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
        });
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
          default:
            // code blo
        }
    });
    $('#feedback').click(function(){
            var url = $(this).data('link');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.feedback_body').html(data);
                }
            });
        });


        @if(Session::get('feedback_add'))
        Lobibox.notify('success', {
            title: "",
            msg: "Successfully added Feedback",
            size: 'mini',
            rounded: true
        });
        <?php
            Session::put("feedback_add",false);
        ?>
    @endif
    function goNotif(id) {
        console.log(id)
    }
</script>