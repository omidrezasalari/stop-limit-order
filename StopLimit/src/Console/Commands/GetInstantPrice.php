<?php

namespace StopLimit\Console\Commands;

use Illuminate\Console\Command;
use StopLimit\Facades\GetInstantPriceFacade;

class GetInstantPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'get:price';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is to get the instant price of Bitcoin';


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
        $this->info("[*] Waiting for instant price .... ");

        GetInstantPriceFacade::receivedThenSend();

        return 1;
    }

}
