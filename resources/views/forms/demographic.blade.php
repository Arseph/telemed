<form id="demographic_form" method="POST">
    {{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name of physician:</label>
            <input type="text" class="form-control" value="" name="name_physician" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Date and Time of Teleconsultation:</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($meeting->date_meeting)->format('M d, Y') }} {{ \Carbon\Carbon::parse($meeting->from_time)->format('h:i A') }}" name="dateandtime" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name and Address of Health Facility<em>(if applicable):</em></label>
            <input type="text" class="form-control" value="" name="address_health">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Name of Telemedicine Partner<em>(if applicable):</em><small><br>If none, Indicate telemedicine platform being used:</small></label>
            <input type="text" class="form-control" value="" name="tele_partner_platform" required>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <label>Prior to teleconsultation proper, obtain patient consent:</label>
        <label class="radio-inline">
          <input type="radio" name="prior_tele_proper" value="1" required>Yes
        </label>
        <label class="radio-inline">
          <input type="radio" name="prior_tele_proper" value="0">No
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label>Is patient accompanied/assisted by another person during the consultation:</label>
        <label class="radio-inline">
          <input type="radio" name="is_patient_accompanied" value="1" required>Yes
        </label>
        <label class="radio-inline">
          <input type="radio" name="is_patient_accompanied" value="0">No
        </label>
    </div>
</div>
<div id="companion" class="row hide">
    <div class="col-md-4">
        <div class="form-group">
            <label>Name of Companion:</label>
            <input type="text" class="form-control" value="" name="name_of_companion">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Relationship:</label>
            <input type="text" class="form-control" value="" name="relationship">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Contact No:</label>
            <input type="text" class="form-control" value="" name="phone_no">
        </div>
    </div>
</div>
</form>