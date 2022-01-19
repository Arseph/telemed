<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingMeeting extends Model
{
    protected $table = 'pending_meetings';
    protected $guarded = array();

    public function patient() {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }

    public function doctor() {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }

    public function encoded() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
