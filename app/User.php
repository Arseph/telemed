<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doctor_id',
        'facility_id',
        'username',
        'password',
        'level',
        'fname',
        'mname',
        'lname',
        'title',
        'contact',
        'email',
        'accrediation_no',
        'accrediation_validity',
        'license_no',
        'prefix',
        'picture',
        'designation',
        'status',
        'last_login',
        'login_status',
        'void'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function facility() {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
        
    }
}
