<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class CovidScreening extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'covid19_screening';
    protected $guarded = array();
}
