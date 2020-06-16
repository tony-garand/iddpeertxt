<?php

namespace peertxt\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CampaignSendFinished implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $userId, $message;

	/**
	 * Create a new event instance.
	 *
	 * @param $userId
	 * @param $message
	 */
	public function __construct($userId, $message)
	{
		$this->userId = $userId;
		$this->message = $message;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('user.' . $this->userId);
	}

	public function broadcastAs()
	{
		return 'job-finished';
	}
}
