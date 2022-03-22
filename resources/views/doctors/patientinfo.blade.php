@extends('layouts.app')

@section('content')
<style>
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .disAble {
        pointer-events:none;
    }
    .image-center {
        width: 35%;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    .heading-info {
        font-weight: bold;
        background: white;
        margin-top: 20px;
        color: #c93600;
        padding: 3px 7px;
        border-top: 1px solid #c93600;
        border-bottom: 1px solid #c93600;
        width: 100%;
        margin-bottom: 20px;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="box box-success">
        <div class="row">
            <div class="col-md-3">
                @if($patient->sex=='Male')
                <img src="{{ asset('public/img/mans.png') }}" class="image-center" />
                @else
                <img src="{{ asset('public/img/womans.png') }}" class="image-center" />
                @endif
                <div><label class="heading-info">Patient Information</label></div>
                <div><label style="text-transform: uppercase;">{{$patient->lname}}, {{$patient->fname}} {{$patient->mname}}</label></div>
                <div style="margin-bottom: 6px;">{{\Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->format('%y years and %m months old and %d day(s)')}}</div>
                <div>Birthdate: <label>{{\Carbon\Carbon::parse($patient->dob)->format('F d, Y')}}</label></div>
                <div>Sex: <label>{{$patient->sex}}</label></div>
                <div>Civil Status: <label>{{$patient->civil_status}}</label></div>
                <hr>
                <div>
                    <ul class="nav nav-pills nav-stacked" style="overflow: auto; height: 350px;">
                      <li><a data-toggle="tab" href="#tabspatientInfo"><img src="{{ asset('public/img/profile.png') }}"/>&nbsp;Profile</a></li>
                      <li class="active"><a data-toggle="tab" href="#tabsTele"><img src="{{ asset('public/img/tele.png') }}"/>&nbsp;Teleconsultations</a></li>
                      <li><a data-toggle="tab" href="#tabsMedHis"><img src="{{ asset('public/img/medhis.png') }}"/>&nbsp;Medical History</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div id="tabspatientInfo" class="tab-pane fade in">
                        <div class="pull-right">
                            <button title="save" type="submit" class="btnSave btn btn-success hide"><i class="far fa-save"></i></button>
                            <h4 id="btnEdit" title="Edit Facility" onclick="enableView()"><a href="javascript:void(0)"><i class="far fa-edit"></i></a></h4>
                        </div>
                        <h3>Patient Profile</h3>
                        <form id="patient_form" class="disAble">
                            <div class="row">
                             <div class="col-sm-6">
                                <label class="reqField">PhilHealth Status:</label>
                                    <select class="form-control select_phic" name="phic_status" required>
                                        <option value="None">None</option>
                                        <option value="Member">Member</option>
                                        <option value="Dependent">Dependent</option>
                                    </select>
                             </div>
                             <div class="col-sm-6">
                                <label>PhilHealth ID:</label>
                                <input type="text" class="form-control phicID" value="" name="phic_id" disabled>
                             </div>
                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label reqField>First Name:</label>
                                    <input type="text" class="form-control" value="{{$patient->fname}}" name="fname" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Middle Name:</label>
                                    <input type="text" class="form-control" value="{{$patient->mname}}" name="mname">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Last Name:</label>
                                    <input type="text" class="form-control" value="{{$patient->lname}}" name="lname" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Contact Number:</label>
                                    <input type="text" class="form-control" value="{{$patient->contact}}" name="contact" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Birth Date:</label>
                                    <input type="date" class="form-control" value="{{$patient->dob}}" min="1910-05-11" max="{{ date('Y-m-d') }}" name="dob" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Sex:</label>
                                    <select class="form-control sex" name="sex" required>
                                        <option value="{{$patient->sex}}" selected>{{$patient->sex}}</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Civil Status:</label>
                                    <select class="form-control civil_status" name="civil_status" required>
                                        <option value="{{$patient->civil_status}}" selected>{{$patient->civil_status}}</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Religion:</label>
                                    <select class="form-control civil_status select2" name="religion" required>
                                        <option value="{{$patient->religion}}" selected>{{$patient->relgion()}}</option>
                                        <option value="AGLIP">AGLIPAY</option><option value="ALLY">ALLIANCE OF BIBLE CHRISTIAN COMMUNITIES</option><option value="ANGLI">ANGLICAN</option><option value="BAPTI">BAPTIST</option><option value="BRNAG">BORN AGAIN CHRISTIAN</option><option value="BUDDH">BUDDHISM</option><option value="CATHO">CATHOLIC</option><option value="XTIAN">CHRISTIAN</option><option value="CHOG">CHURCH OF GOD</option><option value="EVANG">EVANGELICAL</option><option value="IGNIK">IGLESIA NI CRISTO</option><option value="MUSLI">ISLAM</option><option value="JEWIT">JEHOVAHS WITNESS</option><option value="MORMO">LDS-MORMONS</option><option value="LRCM">LIFE RENEWAL CHRISTIAN MINISTRY</option><option value="LUTHR">LUTHERAN</option><option value="METOD">METHODIST</option><option value="PENTE">PENTECOSTAL</option><option value="PROTE">PROTESTANT</option><option value="SVDAY">SEVENTH DAY ADVENTIST</option><option value="UCCP">UCCP</option><option value="UNKNO">UNKNOWN</option><option value="WESLY">WESLEYAN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Educational Attainment:</label>
                                    <select class="form-control civil_status select2" name="edu_attain">
                                        <option value="{{$patient->edu_attain}}" selected>{{$patient->edattain()}}</option>
                                        <option value=""> -- SELECT EDUCATIONAL ATTAINMENT --</option><option value="03">COLLEGE</option><option value="01">ELEMENTARY EDUCATION</option><option value="02">HIGH SCHOOL EDUCATION</option><option value="05">NO FORMAL EDUCATION</option><option value="06">NOT APPLICABLE</option><option value="04">POSTGRADUATE PROGRAM</option><option value="07">VOCATIONAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Occupation:</label>
                                    <input type="text" class="form-control" value="{{$patient->occupation}}" name="occupation">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Monthly Income:</label>
                                    <input type="text" class="form-control" value="{{$patient->monthly_income}}" name="monthly_income">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Select ID:</label>
                                    <select class="form-control id_type select21" name="id_type">
                                        <option value="{{$patient->id_type}}">{{$patient->idtype()}}</option>
                                        <option value="umid">UMID</option>
                                        <option value="dl">DRIVER'S LICENSE</option>
                                        <option value="passport">PASSPORT ID</option>
                                        <option value="postal">POSTAL ID</option>
                                        <option value="tin">TIN ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label id="selectID" class="reqField">CRN:</label>
                                    <input id="idVal" name="id_type_no" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Nationality:</label>
                                    <select class="form-control select2" name="nationality_id" required>
                                        <option value="{{ $patient->nationality->num_code }}" selected>{{ $patient->nationality->nationality }}</option>
                                          @foreach($nationality as $n)
                                            <option value="{{ $n->num_code }}">{{ $n->nationality }}</option>
                                             @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>House no./Lot/Bldg:</label>
                                    <input type="text" class="form-control" value="" name="house_no">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Street:</label>
                                    <input type="text" class="form-control" value="" name="street">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Region:</label>
                                    <select class="form-control select2" name="region" id="region" required>
                                        <option value="{{ $patient->reg->reg_code }}" selected>{{ $patient->reg->reg_desc }}</option>
                                        <option value="">Select Region...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Province:</label>
                                    <select class="form-control select2" name="province" id="province">
                                        <option value="{{ $patient->prov->prov_psgc }}" selected>{{ $patient->prov->prov_name }}</option>
                                        <option value="">Select Province...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="reqField">Municipality:</label>
                                    <select class="form-control muncity filter_muncity select2" name="muncity" id="municipality" required>
                                        <option value="{{ $patient->muni->muni_psgc }}" selected>{{ $patient->muni->muni_name }}</option>
                                        <option value="">Select Municipal/City...</option>
                                          @foreach($municity as $m)
                                            <option value="{{ $m->muni_psgc }}">{{ $m->muni_name }}</option>
                                             @endforeach 
                                         <option value="others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group barangay_holder">
                                    <label>Barangay:</label>
                                    <select class="form-control barangay select2" name="brgy" required>
                                        <option value="{{ $patient->barangay->brg_psgc }}" selected>{{ $patient->barangay->brg_name }}</option>
                                        <option value="">Select Barangay...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="has-group others_holder">
                                    <label>Complete Address :</label>
                                    <input type="text" name="address" value="{{ $patient->address }}" class="form-control others" placeholder="Enter complete address..." />
                                </div>
                            </div>
                         </div>
                        </form>
                    </div>
                    <div id="tabsTele" class="tab-pane fade in active">
                        <h3>Teleconsultations</h3>
                        <br>
                        @if(count($patient->allmeetings)>0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr class="bg-black">
                                    <th>Date</th>
                                    <th>Time:</th>
                                    <th>Type of consultation</th>
                                    <th>Chief Complaint</th>
                                    <th>Attending Provider</th>
                                </tr>
                                @foreach($patient->allmeetings as $row)
                                    <tr>
                                      <td>{{ \Carbon\Carbon::parse($row->date_meeting)->format('l, F d, Y') }}</td>
                                      <td>{{ \Carbon\Carbon::parse($row->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($row->to_time)->format('h:i A') }}</td>
                                      <td>{{$row->pendmeet->telecategory->category_name}}</td>
                                      <td>{{$row->title}}</td>
                                      <td>{{$row->doctor->lname}}, {{$row->doctor->fname}} {{$row->doctor->mname}}</td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="pagination">
                                {{ $patient->allmeetings()->paginate(15)->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <span class="text-warning">
                                <i class="fa fa-warning"></i> No Teleconsultations found!
                            </span>
                        </div>
                    @endif
                    </div>
                    <div id="tabsMedHis" class="tab-pane fade in">
                        <h3>Medical History</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function enableView() {
        $('#patient_form').removeClass('disAble');
        $( '.btnSave' ).removeClass('hide');
        $( '#btnEdit' ).addClass('hide');
        $( 'input[name="fname"]' ).focus();
        $( '.reqField' ).addClass('required-field');
    }
</script>
@endsection

