<?php namespace App\Domain\Contracts;

use Money\Currency;
use Singularity\Foundation\Basket\Contracts\TaxRate;

interface Jurisdiction
{
    /**
     * Return the Tax Rate
     *
     * @return TaxRate
     */
    public function rate();

    /**
     * Return the currency
     *
     * @return Currency
     */
    public function currency();
}
