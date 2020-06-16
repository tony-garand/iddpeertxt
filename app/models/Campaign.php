<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Campaign extends Model
{
	use SoftDeletes, HasTags;

	public function Company()
	{
		return $this->belongsTo('peertxt\models\Company');
	}

	public function MessagingService()
	{
		return $this->belongsTo(MessagingService::class);
	}
	public function CreatedBy()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}

	public function shortlinkCountRelation()
	{
		return $this->hasOne('peertxt\models\Shortlink')->selectRaw('campaign_id, count(*) as count')->groupBy('campaign_id');
	}

	public function getShortlinkCountAttribute()
	{
		return $this->shortlinkCountRelation->count;
	}

	public function Shortlink()
	{
		return $this->hasMany('peertxt\models\Shortlink');
	}

	public function CampaignContacts()
	{
		return $this->hasMany(CampaignContact::class);
	}

	public function getSmsSentCountAttribute()
	{
		return $this->CampaignContacts()->where('cc_status', 50)->count();
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

    public function delivery_rule()
    {
        return $this->hasMany(CampaignDeliveryRule::class);
    }
}
