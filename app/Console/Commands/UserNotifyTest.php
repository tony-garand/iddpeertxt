<?php

namespace peertxt\Console\Commands;

use Auth;
use Illuminate\Console\Command;
use peertxt\Events\CampaignCreateFinished;
use peertxt\Events\UserTestEvent;
use peertxt\models\User;

class UserNotifyTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:notify {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('id');
        broadcast(new UserTestEvent($userId, 'test message'));
    }
}
