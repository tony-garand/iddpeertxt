<?php

namespace peertxt\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneVerificationFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId, $message, $verified, $numberOfContacts;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$numberOfContacts = null, $verified = null, $userId = null)
    {
        $this->message = $message;
        $this->userId = $userId;
        $this->verified = $verified;
        $this->numberOfContacts = $numberOfContacts;
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
        return 'verification-finished';
    }
}
