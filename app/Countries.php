<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Countries extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'countries';
    protected $guarded = array();
}
