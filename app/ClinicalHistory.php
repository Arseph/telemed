<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ClinicalHistory extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'clinical_histories';
    protected $guarded = array();
}
