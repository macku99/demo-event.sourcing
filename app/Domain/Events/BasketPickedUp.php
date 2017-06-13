<?php namespace App\Domain\Events;

use App\Domain\BasketId;
use App\Domain\Contracts\Jurisdiction;
use Singularity\Foundation\EventStore\Contracts\DomainEvent;

class BasketPickedUp implements DomainEvent
{
    /**
     * @var BasketId
     */
    public $basketId;

    /**
     * @var Jurisdiction
     */
    public $jurisdiction;

    /**
     * @param BasketId     $basketId
     * @param Jurisdiction $jurisdiction
     */
    public function __construct(BasketId $basketId, Jurisdiction $jurisdiction)
    {
        $this->basketId = $basketId;
        $this->jurisdiction = $jurisdiction;
    }
}
