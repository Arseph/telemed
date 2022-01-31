<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class DiagMainCat extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'diagnosis_main_categories';
    protected $guarded = array();
}
