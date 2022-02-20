<?php

namespace Omidrezasalari\StopLimitTest;

use Illuminate\Support\Str;
use Login\Entities\User;
use Omidrezasalari\StopLimit\Facades\StopLimitEventFacade;
use Omidrezasalari\StopLimit\Models\StopLimit;
use Mockery;

class StopLimitTest extends TestCase
{
    /**
     * mock variable for StopLimitRepositoryInterface.
     *
     * @var SomeClass|PHPUnit_Framework_MockObject_MockObject
     */

    private $stopLimitMock;

    /**
     * mock variable for ResponderInterface.
     *
     * @var SomeClass|PHPUnit_Framework_MockObject_MockObject
     */
    private $responderMock;

    /**
     * mock variable for CacheRepositoryInterface.
     *
     * @var SomeClass|PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheMock;


    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->stopLimitMock = Mockery::mock('StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface');
        $this->cacheMock = Mockery::mock('StopLimit\Http\Repositories\Cache\CacheRepositoryInterface');
        $this->responderMock = Mockery::mock('StopLimit\Interfaces\ResponderInterface');
    }

    public function test_for_create_new_stop_limit_order_successfully()
    {
        User::unguard();
        StopLimit::unguard();

        $requests = [
            'stop-price' => "30000",
            'limit-price' => "28000",
            'amount' => "0.02",
            'type' => 0
        ];

        $this->stopLimitMock->shouldReceive('store')
            ->once()
            ->with($requests)
            ->andReturn($order = new StopLimit([
                'stop-price' => "30000",
                'limit-price' => "28000",
                'amount' => "0.02",
                'type' => 0,
                'client_order_id' => Str::uuid()
            ]));

        $this->app->instance(
            'StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface',
            $this->stopLimitMock
        );

        StopLimitEventFacade::shouldReceive('dispatch')->with($order)->once();

        $this->responderMock->shouldReceive('orderCreated')->once()->withNoArgs()->andReturn("hello");

        $this->app->instance('StopLimit\Interfaces\ResponderInterface', $this->responderMock);

        $res = $this->actingAs(new User(['id' => 1, 'email' => "testUser2@gmail.com"]))
            ->json('POST', '/api/v1/stop-limits', $requests);

        $this->assertTrue($res->content() == "hello");
    }
}
