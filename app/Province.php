<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Province extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'provinces';
    protected $guarded = array();
}
