<?php namespace App\Domain\MetaData;

use App\Domain\Basket;
use Money\Money;

class TaxMetaData
{
    /**
     * Generate the Meta Data.
     *
     * @param  Basket $basket
     * @return Money
     */
    public function generate(Basket $basket)
    {
        $taxableSubtotal = new Money(0, $basket->currency());

        foreach ($basket->products() as $product) {
            if ($product->taxable) {
                $taxableSubtotal = $taxableSubtotal->add($product->subtotal());
            }
        }

        return $taxableSubtotal->multiply($basket->taxRate()->float());
    }

    /**
     * Return the name of the Meta Data.
     *
     * @return string
     */
    public function name()
    {
        return 'tax';
    }
}
