<?php namespace App\Domain;

use App\Domain\MetaData\SubtotalMetaData;
use App\Domain\MetaData\TaxMetaData;
use App\Domain\MetaData\TotalMetaData;

class Processor
{

    /**
     * @param  Basket $basket
     * @return array
     */
    public function process(Basket $basket)
    {
        $meta = $this->meta($basket);
        $products = $this->products($basket);

        return compact('meta', 'products');
    }

    /**
     * Process basket meta data
     *
     * @param  Basket $basket
     * @return array
     */
    protected function meta(Basket $basket)
    {
        $meta = [];
        $metadata = [new SubtotalMetaData(), new TaxMetaData(), new TotalMetaData()];

        foreach ($metadata as $item) {
            $meta[$item->name()] = $item->generate($basket);
        }

        return $meta;
    }

    /**
     * Process basket products
     *
     * @param  Basket $basket
     * @return array
     */
    protected function products(Basket $basket)
    {
        return $basket->products()->map(function (Product $product) use ($basket) {
            return [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => $product->quantity,
                'freebie'  => $product->freebie,
                'taxable'  => $product->taxable,
                'price'    => $product->price,
                'value'    => $product->value(),
                'subtotal' => $product->subtotal(),
            ];
        })->all();
    }
}
