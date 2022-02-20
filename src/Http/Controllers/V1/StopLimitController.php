<?php

namespace Omidrezasalari\StopLimit\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Omidrezasalari\StopLimit\Facades\StopLimitEventFacade;
use Omidrezasalari\StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface;
use Omidrezasalari\StopLimit\Http\Requests\CreateStopLimitRequest;
use Omidrezasalari\StopLimit\Interfaces\ResponderInterface;

class StopLimitController extends Controller
{
    /**
     * @var StopLimitRepositoryInterface $stopLimitRepository
     */
    private $stopLimitRepository;

    /**
     * @var ResponderInterface $responder
     */
    private $responder;

    /**
     * StopLimitController constructor.
     * @param StopLimitRepositoryInterface $stopLimitRepository
     * @param ResponderInterface $responder
     */
    public function __construct(StopLimitRepositoryInterface $stopLimitRepository, ResponderInterface $responder)
    {
        $this->stopLimitRepository = $stopLimitRepository;
        $this->responder = $responder;
    }

    /**
     * Store new stop limit orders
     *
     * @param CreateStopLimitRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateStopLimitRequest $request)
    {
        $stopLimitOrder = $this->stopLimitRepository->store($request->all());

        StopLimitEventFacade::dispatch($stopLimitOrder);

        return $this->responder->orderCreated();
    }
}