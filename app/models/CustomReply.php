<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomReply extends Model
{
	use SoftDeletes;

	public function Company() {
		return $this->belongsTo('peertxt\models\Company');
	}
}
