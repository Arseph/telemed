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
    .btnSaveMove {
        position: fixed;
        bottom: 30px;
        right: 92px;
        z-index: 99;
        animation-name: fadeInOpacity;
        animation-iteration-count: 1;
        animation-timing-function: ease-in;
        animation-duration: 0.5s;
    }
    @keyframes fadeInOpacity {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
<div class="container">
    <div class="box box-success">
        <div class="box-header with-border">
        <form id="covid_form" method="POST">
            <div class="pull-right">
                <button title="save" type="submit" class="btnSave btn btn-success hide"><i class="far fa-save"></i></button>
                <h4 id="btnEdit" title="Edit Facility" onclick="enableView()"><a href="#"><i class="far fa-edit"></i></a></h4>
            </div>
            <h3 class="text-success">Covid-19 Screening</h3>
            <h4 class="text-primary">Patient: {{ $patient->lname }}, {{ $patient->fname }} {{ $patient->mname }}</h4>
        </div>
        <div id="formEdit" class="box-body disAble">
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <input type="hidden" name="screen_id" value="@if($patient->covidscreen){{ $patient->covidscreen->id }} @endif">
            <input type="hidden" name="assess_id" value="@if($patient->covidassess){{ $patient->covidassess->id }} @endif">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <h4>Overseas Employment Address(for Overseas Filipino Workers)</h4>
                        <hr>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Employer's Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->employers_name }} @endif" name="employers_name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Place Of Work:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->place_of_work }} @endif" name="place_of_work">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>House #/Bldg Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->house_bldg_name }} @endif" name="house_bldg_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Street:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->street }} @endif" name="street">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>City/Municipality:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->municipal }} @endif" name="municipal">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Province/State:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->province }} @endif" name="province">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country:</label>
                            <select name="country_id" class="select2">
                                <option value="">Select Country</option>
                                @foreach($countries as $row)
                                    <option value="{{ $row->num_code }}" @if($patient->covidscreen)@if($patient->covidscreen->country_id == $row->num_code)selected @endif @endif>{{ $row->en_short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Office Phone No:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->office_phone_no }} @endif" name="office_phone_no">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Office Cellphone No:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->cellphone_no }} @endif" name="cellphone_no">
                        </div>
                    </div>
                    <div class="col-md-12"><hr>
                        <h4>Travel History</h4>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <label>History of Travel/Visit/Work in other countries with known COVID-19 transmission 14 days prior to onset of signs and symptoms:</label>
                        <label class="radio-inline"><input type="radio" name="history_travel_country_symptoms" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->history_travel_country_symptoms == 1)checked @endif @endif>Yes</label>
                        <label class="radio-inline"><input type="radio" name="history_travel_country_symptoms" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->history_travel_country_symptoms == 0)checked @endif @endif>No</label>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                            <label>Port of Exit:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->port_of_exit }} @endif" name="port_of_exit">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                            <label>Airline/Sea Vessel:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->airline_sea_vessel }} @endif" name="airline_sea_vessel">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                            <label>Flight/Vessel #:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->flight_vessel_no }} @endif" name="flight_vessel_no">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Departure:</label>
                            <input type="text" class="form-control daterange" value="{{ $date_departure }}" name="date_departure">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Arrival in Philippines:</label>
                            <input type="text" class="form-control daterange" value="{{ $date_arrival_ph }}" name="date_arrival_ph">
                        </div>
                    </div>
                    <div class="col-md-12"><hr>
                        <h4>Exposure History</h4>
                        <hr>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <label>Known Covid-19 Case:</label>&nbsp;
                        <label class="radio-inline"><input type="radio" name="known_covid_case" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->known_covid_case == 1)checked @endif @endif>Yes</label>
                        <label class="radio-inline"><input type="radio" name="known_covid_case" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->known_covid_case == 0)checked @endif @endif>No</label>
                        <label class="radio-inline"><input type="radio" name="known_covid_case" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->known_covid_case == 2)checked @endif @endif>Unknown</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>(If yes) Date of Contact with Known Covid-19 Case:</label>
                            <input type="text" class="form-control daterange" value="{{ $date_contact }}" name="date_contact_known_covid_case" disabled>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Accomodation:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="accomodation" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->accomodation == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="accomodation" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->accomodation == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="accomodation" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->accomodation == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Specific Type:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->acco_specify_type }} @endif" name="acco_specify_type">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->acco_address }} @endif" name="acco_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $acco_date_last_expose }}" name="acco_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->acco_name }} @endif" name="acco_name">
                        </div>
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" name="acco_name_type" value="1" @if($patient->covidscreen)@if($patient->covidscreen->acco_name_type == 1)checked @endif @endif>Guest</label>
                            <label class="radio-inline"><input type="radio" name="acco_name_type" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->acco_name_type == 0)checked @endif @endif>Hotel Worker</label>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Food Establishment:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="food_establishment" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->food_establishment == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="food_establishment" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->food_establishment == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="food_establishment" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->food_establishment == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Specific Type:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->food_es_specify_type }} @endif" name="food_es_specify_type">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->food_es_address }} @endif" name="food_es_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $food_es_date_last_expose }}" name="food_es_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->food_es_name }} @endif" name="food_es_name">
                        </div>
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" name="food_es_name_type" value="1" @if($patient->covidscreen)@if($patient->covidscreen->food_es_name_type == 1)checked @endif @endif>Diner</label>
                            <label class="radio-inline"><input type="radio" name="food_es_name_type" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->food_es_name_type == 0)checked @endif @endif>Crew</label>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Store:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="store" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->store == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="store" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->store == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="store" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->store == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Specific Type:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->store_specify_type }} @endif" name="store_specify_type">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->store_address }} @endif" name="store_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $store_date_last_expose }}" name="store_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->store_name }} @endif" name="store_name">
                        </div>
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" name="store_name_type" value="1" @if($patient->covidscreen)@if($patient->covidscreen->store_name_type == 1)checked @endif @endif>Customer</label>
                            <label class="radio-inline"><input type="radio" name="store_name_type" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->store_name_type == 0)checked @endif @endif>Worker</label>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Health Facility:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="facility" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->facility == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="facility" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->facility == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="facility" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->facility == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Specific Type:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->fac_specify_type }} @endif" name="fac_specify_type">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->fac_address }} @endif" name="fac_address">
                        </div>
                        <div class="form-group">
                            <label>Significant Other:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->fac_significant_other }} @endif" name="fac_significant_other">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $fac_date_last_expose }}" name="fac_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->fac_name }} @endif" name="fac_name">
                        </div>
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" name="fac_name_type" value="1" @if($patient->covidscreen)@if($patient->covidscreen->fac_name_type == 1)checked @endif @endif>Patient</label>
                            <label class="radio-inline"><input type="radio" name="fac_name_type" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->fac_name_type == 0)checked @endif @endif>Health Worker</label>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Event:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="event" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->event == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="event" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->event == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="event" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->event == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Specific Type:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->event_specify_type }} @endif" name="event_specify_type">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $event_date_last_expose }}" name="event_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Event Place:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->event_place }} @endif" name="event_place">
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Workplace:</label>&nbsp;
                            <label class="radio-inline"><input type="radio" name="workplace" value="1" required @if($patient->covidscreen)@if($patient->covidscreen->workplace == 1)checked @endif @endif>Yes</label>
                            <label class="radio-inline"><input type="radio" name="workplace" value="0"  @if($patient->covidscreen)@if($patient->covidscreen->workplace == 0)checked @endif @endif>No</label>
                            <label class="radio-inline"><input type="radio" name="workplace" value="2"  @if($patient->covidscreen)@if($patient->covidscreen->workplace == 2)checked @endif @endif>Unknown</label>
                        </div>
                        <div class="form-group">
                            <label>Company Name:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->wp_company_name }} @endif" name="wp_company_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Last Exposure:</label>
                            <input type="text" class="form-control daterange" value="{{ $wp_date_last_expose }}" name="wp_date_last_expose">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="@if($patient->covidscreen){{ $patient->covidscreen->wp_address }} @endif" name="wp_address">
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-12">
                        <label>List of names of persons in contact with during any of this occasions and their contact numbers:</label><br>
                        <button type="button" class="btnAddrow btn btn-success hide">Add row</button>
                        <br>
                        <br>
                        <div id="nameContact">
                            @if(count($list_name_occasion) > 0)
                            @foreach($list_name_occasion as $row)
                            <div class="inputRow input-group">
                                <input type="text" name="list_name_occasion[]" class="form-control" placeholder="e.g John Doe - 1234567890" value="{{ $row }}">
                                <div class="input-group-btn">
                                  <button class="btnRemoveRow btn btn-danger" type="button">Remove</button>
                                </div>
                            </div>
                            <br>
                            @endforeach
                            @endif  
                        </div>  
                    </div>
                </div>
        </form>
        </div>
    </div>
</div>
@endsection
@section('js')
    @include('admin.scripts.patient')
@endsection

