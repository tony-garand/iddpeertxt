<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shortlink extends Model
{
	use SoftDeletes;

	public function Campaign()
	{
		return $this->belongsTo('peertxt\models\Campaign');
	}

	public function ShortlinkClick()
	{
		return $this->hasOne('peertxt\models\ShortlinkClick');
	}
		
	public function hasClick()
	{
		if (!is_null($this->ShortlinkClick))
			return true;
		else
			return false;
	}
}
