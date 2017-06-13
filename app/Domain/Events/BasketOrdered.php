<?php namespace App\Domain\Events;

use App\Domain\BasketId;
use App\Domain\Customer;
use Singularity\Foundation\EventStore\Contracts\DomainEvent;

class BasketOrdered implements DomainEvent
{
    /**
     * @var BasketId
     */
    public $basketId;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var string
     */
    public $status;

    /**
     * @param BasketId $basketId
     * @param Customer $customer
     */
    public function __construct(BasketId $basketId, Customer $customer)
    {
        $this->basketId = $basketId;
        $this->customer = $customer;
        $this->status = 'ordered';
    }
}
