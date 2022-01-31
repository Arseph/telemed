<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class DiagSubCat extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'diagnosis_sub_categories';
    protected $guarded = array();
}
