<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Contact extends Model
{
	use HasTags, SoftDeletes;

	const VerifiedPhoneNo = 0;
	const VerifiedPhoneValidPhone = 1;
	const VerifiedPhoneIsMobile = 2;

	public function Company()
	{
		return $this->belongsTo('peertxt\models\Company');
	}

	public function ActionLog()
	{
		return $this->hasMany('peertxt\models\ContactActionLog');
	}

	public function tagsToString()
	{
		$tags = "";
		foreach ($this->tags as $tag) {
			$tags .= $tag->name . ", ";
		}
		$tags = substr(trim($tags), 0, strlen(trim($tags)) - 1);

		return $tags;
	}
}
