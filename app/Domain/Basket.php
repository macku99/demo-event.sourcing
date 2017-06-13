<?php namespace App\Domain;

use App\Domain\Contracts\Jurisdiction;
use App\Domain\Contracts\TaxRate;
use App\Domain\Events;
use Illuminate\Support\Collection;
use Money\Currency;
use Singularity\Foundation\Contracts\Identifier;
use Singularity\Foundation\EventStore\AggregateRoot\AggregateRoot;

class Basket extends AggregateRoot
{
    /**
     * @var BasketId
     */
    protected $basketId;

    /**
     * @var TaxRate
     */
    protected $taxRate;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var Collection
     */
    protected $products;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var string
     */
    protected $status;

    /**
     * @return Identifier
     */
    public function id()
    {
        return $this->basketId;
    }

    /**
     * @return TaxRate
     */
    public function taxRate()
    {
        return $this->taxRate;
    }

    /**
     * @return Currency
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return Collection
     */
    public function products()
    {
        return $this->products;
    }

    /**
     * @param BasketId     $basketId
     * @param Jurisdiction $jurisdiction
     */
    public static function create(BasketId $basketId, Jurisdiction $jurisdiction)
    {
        return (new static)->pickUp($basketId, $jurisdiction);
    }

    /**
     * @param  BasketId     $basketId
     * @param  Jurisdiction $jurisdiction
     * @return $this
     */
    public function pickUp(BasketId $basketId, Jurisdiction $jurisdiction)
    {
        return $this->recordThat(
            new Events\BasketPickedUp($basketId, $jurisdiction)
        );
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function pickedUpBy(Customer $customer)
    {
        return $this->recordThat(
            new Events\BasketCustomerSet($this->id(), $customer)
        );
    }

    /**
     * @param  Product $product
     * @return $this
     * @throws \Exception
     */
    public function put(Product $product)
    {
        if ($product->quantity < 1) {
            throw new \Exception('Basket should contain at least one unit of a product');
        }

        return $this->recordThat(
            new Events\ProductPut($this->id(), $product)
        );
    }

    /**
     * @return $this
     */
    public function order()
    {
        return $this->recordThat(
            new Events\BasketOrdered($this->id(), $this->customer)
        );
    }

    /**
     * @param  Events\BasketPickedUp $event
     * @return $this
     */
    protected function whenBasketPickedUp(Events\BasketPickedUp $event)
    {
        $this->basketId = $event->basketId;
        $this->taxRate = $event->jurisdiction->rate();
        $this->currency = $event->jurisdiction->currency();

        $this->products = new Collection;

        return $this;
    }

    /**
     * @param  Events\BasketCustomerSet $event
     * @return $this
     */
    protected function whenBasketCustomerSet(Events\BasketCustomerSet $event)
    {
        $this->customer = $event->customer;

        return $this;
    }

    /**
     * @param  Events\ProductPut $event
     * @return $this
     */
    protected function whenProductPut(Events\ProductPut $event)
    {
        $this->products = $this->products
            ->reject(function (Product $product) use ($event) {
                return $product->equals($event->product);
            })->push($event->product);

        return $this;
    }

    /**
     * @param  Events\BasketOrdered $event
     * @return $this
     */
    protected function whenBasketOrdered(Events\BasketOrdered $event)
    {
        $this->status = $event->status;

        return $this;
    }
}
