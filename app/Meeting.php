<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; 
class Meeting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'meetings';
    protected $guarded = array();

    public function patient() {
    	return $this->hasOne(Patient::class, 'id', 'patient_id');
    }

    public function doctor() {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }

    public function pendmeet() {
        return $this->hasOne(PendingMeeting::class, 'meet_id', 'id');
    }

    public function encoded() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function docorder() {
        return $this->hasOne(DoctorOrder::class, 'meet_id', 'id');
    }
}
