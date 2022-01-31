<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class LabRequest extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'ref_labrequest';
    protected $guarded = array();
}
