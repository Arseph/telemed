<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('public/img/dohro12logo2.png') }}">
    <meta http-equiv="cache-control" content="max-age=0" />
    <title>DOH CHD XII – Telemedicine</title>
    <!-- <title>{{ (isset($title)) ? $title : 'Referral System'}}</title> -->
    <!-- SELECT 2 -->
    <link href="{{ asset('public/plugin/select2/select2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/bootstrap-theme.min.css') }}" rel="stylesheet">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('public/plugin/Ionicons/css/ionicons.min.css') }}">

    <!-- Font awesome -->
    <link href="{{ asset('public/assets/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/fontawesome/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/fontawesome/css/v5-font-face.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/fontawesome/css/v4-font-face.css') }}" rel="stylesheet">
    <link href="{{ asset('public/plugin/select2/select2.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('public/assets/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css/AdminLTE.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap-clockpicker.min.css') }}">
    <link href="{{ asset('public/plugin/datepicker/datepicker3.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/plugin/Lobibox/lobibox.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('public/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link href="{{ asset('public/plugin/daterangepicker_old/daterangepicker-bs3.css') }}" rel="stylesheet">

    <link href="{{ asset('public/plugin/table-fixed-header/table-fixed-header.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>
    <title>
        @yield('title','Home')
    </title>

    @yield('css')
    @include('teleconsult.css.form')
</head>

<body>
<div id="teleView"></div>
<button id="myBtn" title="Teleconsultation forms"><i class="fas fa-file"></i></button>
<button type="button" data-toggle="tooltip" data-placement="left" title="Demographic Profile" class="btnDemo btn btn-primary" onclick="showForm('demoDiv', 'btnSaveDemo')"><i class="fa-solid fa-address-card"></i></button>
<button type="button" data-toggle="tooltip" data-placement="left" title="Clinical History and Physical Examination"  class="btnClinical btn btn-primary" onclick="showForm('cliDiv', 'btnSaveClinical')"><i class="fa-solid fa-book-medical"></i></button>
<button type="button" data-toggle="tooltip" data-placement="left" title="Covid-19 Screening" class="btnCovid btn btn-primary" onclick="showForm('covDiv', 'btnSaveCovid')"><i class="fa-solid fa-virus"></i></button>
<button type="button"data-toggle="tooltip" data-placement="left" title="Diagnosis/Assessment" class="btnDiagnosis btn btn-primary" onclick="showForm('diagDiv', 'btnSaveDiag')"><i class="fa-solid fa-person-dots-from-line"></i></button>
<button type="button" data-toggle="tooltip" data-placement="left" title="Plan of Management" class="btnPlan btn btn-primary" onclick="showForm('planDiv', 'btnSavePlan')"><i class="fa-solid fa-file-medical"></i></button>
<input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
<input type="hidden" name="patient_id" value="{{ $patient->id }}">
<input type="hidden" name="demographic_id" value="@if($patient->demoprof){{ $patient->demoprof->id }} @endif">
<input type="hidden" name="clinical_id" value="@if($patient->clinical){{ $patient->clinical->id }} @endif">
<input type="hidden" name="covidassess_id" value="@if($patient->covidassess){{ $patient->covidassess->id }} @endif">
<input type="hidden" name="covidscreen_id" value="@if($patient->covidscreen){{ $patient->covidscreen->id }} @endif">
<input type="hidden" name="diagassess_id" value="@if($patient->diagassess){{ $patient->diagassess->id }} @endif">
<input type="hidden" name="planmanage_id" value="@if($patient->planmanage){{ $patient->planmanage->id }} @endif">
<input type="hidden" name="phy_id" value="@if($patient->phyexam){{ $patient->phyexam->id }} @endif">
<div id="demoDiv" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('demoDiv', 'btnSaveDemo')">&times;</a>
  <div class="">
        <div class="box-header with-border" style="background-color: #00a65a; color: white;">
            <h4 style="">Demographic Profile</h4>
        </div>
        <div class="box-body">
            @include('forms.demographic')
        </div>
    </div>
    <div class="">
        <div class="box-header with-border" style="background-color: #00a65a; color: white;">
            <div class="row">
                <div class="col-md-6">
                    <h4 style="">Patient Profile</h4>
                </div>
                <div class="col-md-6 form-inline">
                    <div class="form-group">
                        <label>Case #:</label>
                        <input type="text" class="form-control" value="{{ $case_no }}" name="case_no" readonly>
                    </div>
                </div>
            </div>
        </div>
        @include('forms.patientprof')
    </div>
</div>
<div id="cliDiv" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('cliDiv', 'btnSaveClinical')">&times;</a>
    <div class="">
        <div class="box-header with-border" style="background-color: #00a65a; color: white;">
            <h4 style="">Clinical History</h4>
        </div>
        <div class="box-body">
            @include('forms.clinical')
        </div>
    </div>
</div>
<div id="covDiv" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('covDiv', 'btnSaveCovid')">&times;</a>
  @include('forms.covid')
</div>
<div id="diagDiv" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('diagDiv', 'btnSaveDiag')">&times;</a>
  @include('forms.diagnosis')
</div>
<div id="planDiv" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('planDiv', 'btnSavePlan')">&times;</a>
  @include('forms.plan')
</div>
<div class="modal fade" id="trans_prescription" role="dialog" aria-labelledby="trans_prescription" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Prescriptions</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tr class="bg-black">
                    <th>Prescription Code</th>
                    <th>Medicine Type</th>
                    <th>Drug Code</th>
                    <th>Frequency</th>
                    <th>Dose Regimen</th>
                    <th>Quantity</th>
                </tr>
                @foreach($prescription as $row)
                    <tr>
                        <td style="white-space: nowrap;">
                            <b class="text-warning" style="cursor: pointer;" onclick="addPres('{{ $row->presc_code }}')">
                                <a>
                                    {{ $row->presc_code }}
                                </a>
                            </b>
                        </td>
                        <td>
                            <b>{{ $row->type_med() }}</b>
                        </td>
                        <td>
                            <b>{{ $row->drugmed->drugcode }}</b>
                        </td>
                        <td>
                            <b>{{ $row->freq() }}</b>
                        </td>
                        <td>
                            <b>{{ $row->dose_reg() }}</b>
                        </td>
                         <td>
                            <b>{{ $row->total_qty }}</b>
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="text-center">
                {{ $prescription->links() }}
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/strophe.js/1.2.16/strophe.min.js"></script>
<script src="{{ asset('js/strophe.disco.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src='https://meet.jit.si/external_api.js'></script>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- <script crossorigin src="https://unpkg.com/webex@^1/umd/webex.min.js"></script> -->
<!-- <script src="{{ asset('resources/views/doctors/scripts/webex_function.js') }}"></script> -->
<script src="{{ asset('public/assets/js/jquery.min.js?v='.date('mdHis')) }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('public/plugin/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap-clockpicker.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery-validate.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('public/assets/js/ie10-viewport-bug-workaround.js') }}"></script>
<script src="{{ asset('public/assets/js/script.js') }}?v=1"></script>

<script src="{{ asset('public/plugin/Lobibox/Lobibox.js') }}?v=1"></script>
<script src="{{ asset('public/plugin/select2/select2.min.js') }}?v=1"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('public/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}?v=1"></script>

<script src="{{ url('public/plugin/daterangepicker_old/moment.min.js') }}"></script>
<script src="{{ url('public/plugin/daterangepicker_old/daterangepicker.js') }}"></script>

<script src="{{ asset('public/assets/js/jquery.canvasjs.min.js') }}?v=1"></script>

<!-- TABLE-HEADER-FIXED -->
<script src="{{ asset('public/plugin/table-fixed-header/table-fixed-header.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
@include('others.scripts.form')
@yield('js')
</body>
</html>