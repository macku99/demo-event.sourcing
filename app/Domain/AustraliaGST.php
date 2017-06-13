<?php namespace App\Domain;

use App\Domain\Contracts\TaxRate;

class AustraliaGST implements TaxRate
{
    /**
     * @var float
     */
    protected $rate;

    /**
     */
    public function __construct()
    {
        $this->rate = 0.10;
    }

    /**
     * Return the Tax Rate as a float
     *
     * @return float
     */
    public function float()
    {
        return $this->rate;
    }

    /**
     * Return the Tax Rate as a percentage
     *
     * @return int
     */
    public function percentage()
    {
        return intval($this->rate * 100);
    }
}
