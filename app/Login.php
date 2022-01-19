<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'logins';
    protected $guarded = array();

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
