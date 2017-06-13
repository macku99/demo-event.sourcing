<?php namespace App\Domain\MetaData;

use App\Domain\Basket;
use Money\Money;

class SubtotalMetaData
{
    /**
     * Generate the Meta Data.
     *
     * @param  Basket $basket
     * @return Money
     */
    public function generate(Basket $basket)
    {
        $subtotal = new Money(0, $basket->currency());

        foreach ($basket->products() as $product) {
            $subtotal = $subtotal->add($product->subtotal());
        }

        return $subtotal;
    }

    /**
     * Return the name of the Meta Data.
     *
     * @return string
     */
    public function name()
    {
        return 'subtotal';
    }
}
