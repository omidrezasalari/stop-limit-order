<?php

namespace StopLimit\Console\Commands;

use Illuminate\Console\Command;
use StopLimit\Models\StopLimit;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FakeStopLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake stop limit order';

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
     * @return int
     */
    public function handle()
    {
        factory(StopLimit::class, 10000)->create();

        $this->info("Fake stop limit records create successfully");

        return 1;
    }
}
