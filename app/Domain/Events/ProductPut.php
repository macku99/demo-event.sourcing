<?php namespace App\Domain\Events;

use App\Domain\BasketId;
use App\Domain\Product;
use Singularity\Foundation\EventStore\Contracts\DomainEvent;

class ProductPut implements DomainEvent
{
    /**
     * @var BasketId
     */
    public $basketId;

    /**
     * @var Product
     */
    public $product;

    /**
     * @param BasketId $basketId
     * @param Product $product
     */
    public function __construct(BasketId $basketId, Product $product)
    {
        $this->basketId = $basketId;
        $this->product = $product;
    }
}
