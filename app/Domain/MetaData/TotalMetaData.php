<?php namespace App\Domain\MetaData;

use App\Domain\Basket;
use Money\Money;

class TotalMetaData
{
    /**
     * Generate the Meta Data.
     *
     * @param  Basket $basket
     * @return Money
     */
    public function generate(Basket $basket)
    {
        $total = (new SubTotalMetaData)->generate($basket);
        $tax = (new TaxMetaData)->generate($basket);

        return $total->add($tax);
    }

    /**
     * Return the name of the Meta Data.
     *
     * @return string
     */
    public function name()
    {
        return 'total';
    }
}
