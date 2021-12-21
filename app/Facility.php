<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $guarded = array();

    public function region() {
        return $this->hasOne(Region::class, 'reg_psgc', 'reg_psgc');
    }
    public function province() {
    	return $this->hasOne(Province::class, 'prov_psgc', 'prov_psgc');
    }
    public function municipal() {
    	return $this->hasOne(MunicipalCity::class, 'muni_psgc', 'muni_psgc');
    }

    public function barangay() {
    	return $this->hasOne(Barangay::class, 'brg_psgc', 'brgy_psgc');
    }
}
