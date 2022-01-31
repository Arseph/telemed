<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class UnitofMes extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'ref_unitofmes';
    protected $guarded = array();
}
