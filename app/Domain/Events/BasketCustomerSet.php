<?php namespace App\Domain\Events;

use App\Domain\BasketId;
use App\Domain\Customer;
use Singularity\Foundation\EventStore\Contracts\DomainEvent;

class BasketCustomerSet implements DomainEvent
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
     * @param BasketId $basketId
     * @param Customer $customer
     */
    public function __construct(BasketId $basketId, Customer $customer)
    {
        $this->basketId = $basketId;
        $this->customer = $customer;
    }
}
