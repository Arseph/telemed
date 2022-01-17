@extends('layouts.app')

@section('content')
<div class="container">
    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3>
                    Welcome to Tele Consultation! Doctor Dashboard
                </h3>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
    $(document).ready(function() {
        var user = {!! json_encode(Session::get('auth')) !!};
        if(user.level == 'doctor') {
            Lobibox.notify('info', {
                size: 'large',
                title: 'Webex Token',
                msg: 'Remember to change your webex token every 12 hours.'
            }); 
        }
    });
    </script>
@endsection