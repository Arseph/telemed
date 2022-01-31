<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class TeleCategory extends Model
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'tele_categories';
    protected $guarded = array();
}
