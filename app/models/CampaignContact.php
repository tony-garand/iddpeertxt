<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignContact extends Model
{
    use SoftDeletes;

	protected $guarded = array();

	public function Contact()
	{
		return $this->belongsTo('peertxt\models\Contact');
	}

	public function Campaign()
	{
		return $this->belongsto('peertxt\models\Campaign');
	}
}
