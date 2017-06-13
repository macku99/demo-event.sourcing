<?php namespace App\Domain;

use App\Domain\Contracts\Jurisdiction;
use App\Domain\Contracts\TaxRate;
use Illuminate\Support\Collection;
use Money\Currency;
use Singularity\Foundation\Contracts\Identifier;

class BasketWithoutEventSourcing
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
        $this->basketId = $basketId;
        $this->taxRate = $jurisdiction->rate();
        $this->currency = $jurisdiction->currency();

        $this->products = new Collection;

        return $this;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function pickedUpBy(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
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

        $this->products = $this->products
            ->reject(function (Product $value) use ($product) {
                return $value->equals($product);
            })->push($product);

        return $this;
    }

    /**
     * @return $this
     */
    public function order()
    {
        $this->status = 'ordered';

        return $this;
    }
}
