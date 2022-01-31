<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class CovidAssessment extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'covid19_clinical_assessment';
    protected $guarded = array();
}
