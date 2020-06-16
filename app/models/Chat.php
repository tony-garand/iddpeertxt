<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

	public function ChatThread()
	{
		return $this->hasMany('peertxt\models\ChatThread');
	}

	public function Company()
	{
		return $this->belongsTo('peertxt\models\Company');
	}

	public function Contact()
	{
		return $this->belongsTo('peertxt\models\Contact');
	}

	public function Campaign()
	{
		return $this->belongsTo('peertxt\models\Campaign');
	}

	public function getLatestThreadAttribute()
	{
		return $this->ChatThread()->latest()->first();
	}

}
