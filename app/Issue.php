<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Issue extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'issue';
    protected $guarded = array();
}
