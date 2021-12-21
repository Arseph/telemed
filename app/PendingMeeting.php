<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingMeeting extends Model
{
    protected $table = 'pending_meetings';
    protected $guarded = array();
}
