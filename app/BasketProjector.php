<?php namespace App;

use App\Domain\BasketId;
use App\Domain\BasketRepository;
use App\Domain\Events;
use App\Domain\Processor;
use Illuminate\Events\Dispatcher;

class BasketProjector
{
    /**
     * @param Events\BasketOrdered $event
     */
    public function whenBasketOrdered(Events\BasketOrdered $event)
    {
        tap(Basket::firstOrNew(['id' => (string) $event->basketId]), function ($basket) use ($event) {
            $basket->id = (string) $event->basketId;
            $basket->user_id = $event->customer->id;
            $basket->fill(
                $this->basketAttributes($event->basketId)
            );
            $basket->status = $event->status;
            $basket->save();
        });
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            Events\BasketOrdered::class,
            'App\BasketProjector@whenBasketOrdered'
        );
    }

    /**
     * @param  BasketId $basketId
     * @return array
     */
    protected function basketAttributes(BasketId $basketId)
    {
        $processor = new Processor;
        $basketSummary = $processor->process(
            resolve(BasketRepository::class)->get($basketId)
        );

        $basketProducts = collect($basketSummary['products'])->map(function ($product) {
            return [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price']->getAmount(),
                'qty'      => $product['quantity'],
                'freebie'  => $product['freebie'],
                'taxable'  => $product['taxable'],
                'value'    => $product['value']->getAmount(),
                'subtotal' => $product['subtotal']->getAmount(),
            ];
        });

        return [
            'subtotal' => $basketSummary['meta']['subtotal']->getAmount(),
            'tax'      => $basketSummary['meta']['tax']->getAmount(),
            'total'    => $basketSummary['meta']['total']->getAmount(),
            'products' => $basketProducts->all(),
        ];
    }
}
