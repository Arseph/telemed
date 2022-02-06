<form id="profile_form" method="POST">
<div class="box-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->lname }}@endif" name="lname" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->fname }}@endif" name="fname" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->mname }}@endif" name="mname" readonly>
            </div>
        </div>
    </div>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Birthday:</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($meeting->patient->dob)->format('m/d/Y') }}" name="bday" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Age:</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($meeting->patient->dob)->diff(\Carbon\Carbon::now())->format('%y years old') }}" name="age" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Sex:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->sex }}@endif" name="mname" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Occupation:</label>
                <input type="text" class="form-control" value="{{ $meeting->occupation }}" name="occupation" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Civil Status:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->civil_status }}@endif" name="civil_status" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->nationality){{ $meeting->patient->nationality->nationality }}@endif" name="nationality" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Philheath No:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->phic_id }}@endif" name="phic_id" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Passport No:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->passport_no }}@endif" name="passport_no" readonly>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Region:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->reg){{ $meeting->patient->reg->reg_desc }}@endif" name="region" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>House No./Lot/Bldg:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->house_no }}@endif" name="house_no" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Street:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->street }}@endif" name="street" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Province:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->prov){{ $meeting->patient->prov->prov_name }}@endif" name="province" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Municipality:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->muni){{ $meeting->patient->muni->muni_name }}@endif" name="muni_name" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Barangay:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->barangay){{ $meeting->patient->barangay->brg_name }}@endif" name="barangay" readonly>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Contact No:</label>
                <input type="text" class="form-control" value="@if($meeting->patient){{ $meeting->patient->contact }}@endif" name="contact_no" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Email Address:</label>
                <input type="text" class="form-control" value="@if($meeting->patient->account){{ $meeting->patient->account->email }}@endif" name="email" readonly>
            </div>
        </div>
    </div>
</div>
</form>