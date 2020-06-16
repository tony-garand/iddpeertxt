<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;

class MessagingService extends Model
{
	protected $appends = ['number_list'];

    public function Company()
    {
        return $this->belongsTo(Company::class);
    }

    public function Numbers()
		{
			return $this->hasMany(MessagingServiceNumber::class);
		}

		public function getNumberListAttribute()
		{
			return implode(',', $this->Numbers()->limit(5)->pluck('number')->toArray());
		}

		public function Campaign()
		{
			return $this->hasOne(Campaign::class);
		}
}
