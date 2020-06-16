<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;

class LogOutgoing extends Model
{

	protected $guarded = array();

	public function CampaignContact()
	{
		return $this->belongsTo('peertxt\models\CampaignContact');
	}

	public function Campaign()
	{
		return $this->belongsto('peertxt\models\Campaign');
	}
}
