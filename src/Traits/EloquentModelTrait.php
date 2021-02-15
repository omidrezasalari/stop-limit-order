<?php

namespace StopLimit\Traits;

trait EloquentModelTrait
{
    private $readyToProcess = 0;
    private $canceled = 1;
    private $performed = 2;

    public function getTotalPriceAttribute()
    {
        return $this->calculateTotal();
    }

    /**
     * calculate total price field.
     *
     * @return float|int
     */
    private function calculateTotal()
    {

        return $this->getAttribute('limit_price') * $this->getAttribute('amount');
    }

    public function ScopeBuy($query)
    {
        return $query->where('type', true);
    }

    public function ScopeSell($query)
    {
        return $query->where('type', false);
    }

    public function ScopeReadyToProcess($query)
    {
        return $query->where('status', $this->getReadyToProcess());
    }

    public function ScopeCanceled($query)
    {
        return $query->where('status', $this->canceled());
    }

    public function ScopePerformed($query)
    {
        return $query->where('status', $this->performed());
    }

    public function getReadyToProcess()
    {
        return $this->readyToProcess;
    }

    public function canceled()
    {
        return $this->canceled;
    }

    public function performed()
    {
        return $this->performed;
    }

}