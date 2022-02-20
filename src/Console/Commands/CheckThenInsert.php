<?php

namespace Omidrezasalari\StopLimit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Omidrezasalari\StopLimit\Facades\StopLimitProcessFacade;
use Omidrezasalari\StopLimit\Facades\StopLimitQueueFacade;


class CheckThenInsert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:insert {instantPrice}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command was executed to check orders and add them to the queue for execution.';

    /**
     * Create a new command instance.
     *
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
        $instantPrice = $this->argument('instantPrice');
        $logData = ['start_time' => now(), 'instant_price' => $instantPrice];

        list($sellStopLimits, $buyStopLimits) = StopLimitProcessFacade::getValuesFromCache();
        list($fetchSellStopLimitsForFiltered, $fetchBuyStopLimitsForFiltered) =
            StopLimitProcessFacade::isStopOrdersExist($sellStopLimits, $instantPrice, $buyStopLimits);

        $messages = StopLimitProcessFacade::sendToQueues($fetchBuyStopLimitsForFiltered,
            $fetchSellStopLimitsForFiltered, $logData);

        $sellOrderIds = Arr::pluck($fetchSellStopLimitsForFiltered, 'id');
        $buyOrderIds = Arr::pluck($fetchBuyStopLimitsForFiltered, 'id');

        StopLimitProcessFacade::resetCache($sellStopLimits, $sellOrderIds, $buyStopLimits, $buyOrderIds);
        StopLimitQueueFacade::dispatch(Arr::collapse([$sellOrderIds, $buyOrderIds]), $messages['status']);
        return 1;
    }
}
