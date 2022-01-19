<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
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
}
