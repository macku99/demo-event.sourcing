<?php namespace App\Domain;

use Money\Money;

class Product
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Money
     */
    public $price;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var bool
     */
    public $freebie;

    /**
     * @var bool
     */
    public $taxable;

    /**
     * @param int    $id
     * @param string $name
     * @param Money  $price
     * @param int    $quantity
     */
    public function __construct($id, $name, Money $price, $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->freebie = false;
        $this->taxable = true;
    }

    /**
     * Set the product as a freebie.
     *
     * @return $this
     */
    public function isFreebie()
    {
        $this->freebie = true;

        return $this;
    }

    /**
     * Set the product as non taxable.
     *
     * @return $this
     */
    public function isNonTaxable()
    {
        $this->taxable = false;

        return $this;
    }

    /**
     * @return Money
     */
    public function value()
    {
        return $this->price->multiply($this->quantity);
    }

    /**
     * @return Money
     */
    public function subtotal()
    {
        $subtotal = new Money(0, $this->price->getCurrency());

        if (!$this->freebie) {
            $subtotal = $subtotal->add($this->value());
        }

        return $subtotal;
    }

    /**
     * Checks equality with another product.
     *
     * @param  Product $other
     * @return bool
     */
    public function equals(Product $other)
    {
        return $this->isSameClass($other) &&
            $this->id == $other->id;
    }

    /**
     * Determine whether a product has the same class as this.
     *
     * @param  Product $other
     * @return bool
     */
    protected function isSameClass(Product $other)
    {
        return !is_null($other) && get_class($this) == get_class($other);
    }
}