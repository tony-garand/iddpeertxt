<?php

namespace peertxt\Events;

//use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
//use Illuminate\Broadcasting\PrivateChannel;
//use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewClientActivity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client_id, $note;

    /**
     * Create a new event instance.
     *
     * @param int $client_id
     * @param string $note
     *
     * @return void
     */
    public function __construct(int $client_id, string $note)
    {
        $this->client_id = $client_id;
        $this->note = $note;
    }
}
