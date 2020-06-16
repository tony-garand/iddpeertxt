<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use peertxt\models\User;

class ContactActionLog extends Model
{
	public function User()
	{
		return $this->belongsTo(User::class, 'action_by');
	}
}
