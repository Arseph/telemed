<script>
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
        console.log(loc)
    }
    function gourl(id) {
        var url = "{{ url('patient') }}"+"/"+loc+"/"+id;
        console.log(url)
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

</script>