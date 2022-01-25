<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $guarded = array();

    public function nationality() {
    	return $this->hasOne(Countries::class, 'num_code', 'nationality_id');
    }

    public function reg() {
    	return $this->hasOne(Region::class, 'reg_code', 'region');
    }

    public function prov() {
    	return $this->hasOne(Province::class, 'prov_psgc', 'province');
    }

    public function muni() {
    	return $this->hasOne(MunicipalCity::class, 'muni_psgc', 'muncity');
    }

    public function barangay() {
    	return $this->hasOne(Barangay::class, 'brg_psgc', 'brgy');
    }
    public function account() {
        return $this->hasOne(User::class, 'id', 'account_id');
    }
    public function meeting() {
        return $this->hasOne(PendingMeeting::class, 'patient_id', 'id');
    }
    public function clinical() {
        return $this->hasOne(ClinicalHistory::class, 'patient_id', 'id');
    }
    public function covidassessment() {
        return $this->hasOne(CovidAssessment::class, 'patient_id', 'id');
    }
    public function covidscreening() {
        return $this->hasOne(CovidScreening::class, 'patient_id', 'id');
    }
}
