<?php

namespace Omidrezasalari\StopLimit\Console\Commands;

use Illuminate\Console\Command;
use Omidrezasalari\StopLimit\Facades\ReceivedQueueMessagesFacade;

class ReceivedQueueMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command receives orders that are ready to be processed and sends them to the trading engine';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();


    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert(" [*] Waiting for messages [*] ");

        ReceivedQueueMessagesFacade::receivedMessages();

        return 0;
    }
}
