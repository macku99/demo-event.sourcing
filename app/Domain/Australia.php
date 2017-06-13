<?php namespace App\Domain;

use App\Domain\Contracts\Jurisdiction;
use App\Domain\Contracts\TaxRate;
use Money\Currency;

class Australia implements Jurisdiction
{
    /**
     * @var TaxRate
     */
    protected $tax;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     */
    public function __construct()
    {
        $this->tax = new AustraliaGST();
        $this->currency = new Currency('AUD');
    }

    /**
     * @return Contracts\TaxRate
     */
    public function rate()
    {
        return $this->tax;
    }

    /**
     * @return Currency
     */
    public function currency()
    {
        return $this->currency;
    }
}
