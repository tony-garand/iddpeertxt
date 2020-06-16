<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;

class ChatThread extends Model
{

	protected $touches = ['chat'];

	public function Chat()
	{
		return $this->belongsTo('peertxt\models\Chat');
	}

}
