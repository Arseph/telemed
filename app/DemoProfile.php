<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class DemoProfile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'demographic_profile';
    protected $guarded = array();
}
