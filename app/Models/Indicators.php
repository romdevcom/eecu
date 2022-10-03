<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicators extends Model
{
    use HasFactory;

	public function numerator_id(){
		return $this->belongsTo('App\Models\Measures');
	}

	public function denominator_id(){
		return $this->belongsTo('App\Models\Measures');
	}
}
