<?php

namespace peertxt\Listeners;

use peertxt\Events\NewClientActivity;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;
use peertxt\models\ClientNote;

class CreateClientNote
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewClientActivity  $event
     * @return void
     */
    public function handle(NewClientActivity $event)
    {
        $user = auth()->user();
        try {
            $clientNote = new ClientNote();
            $clientNote->note = create_user_link($user) . $event->note;
            $clientNote->manual = 0;
            $clientNote->client_id = $event->client_id;
            $clientNote->author_id = $user->id;

            if (!$clientNote->save())
                throw new Exception('Unable to insert new client note');

        } catch(Exception $e) {
            Log::error('CreateClientNote Listener Error: '.$e->getMessage());
        }
    }
}
