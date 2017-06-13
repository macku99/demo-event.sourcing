<?php namespace App;

use App\Domain\BasketId;
use App\Domain\BasketRepository;
use App\Domain\Events;
use App\Domain\Processor;
use Illuminate\Events\Dispatcher;

class EnhancedBasketProjector
{
    /**
     * @param Events\BasketPickedUp $event
     */
    public function whenBasketPickedUp(Events\BasketPickedUp $event)
    {
        $basket = new Basket;
        $basket->id = (string) $event->basketId;
        $basket->save();
    }

    /**
     * @param Events\BasketCustomerSet $event
     */
    public function whenBasketCustomerSet(Events\BasketCustomerSet $event)
    {
        tap(User::firstOrNew(['id' => (string) $event->customer->id]), function ($user) use ($event) {
            $user->id = (string) $event->customer->id;
            $user->fill([
                'name'  => $event->customer->name,
                'email' => $event->customer->email,
            ]);
            $user->save();

            $basket = Basket::find((string) $event->basketId);
            $user->baskets()->save($basket);
        });
    }

    /**
     * @param Events\ProductPut $event
     */
    public function whenProductPut(Events\ProductPut $event)
    {
        $basket = Basket::find((string) $event->basketId);
        $basket->fill(
            $this->basketAttributes($event->basketId)
        );
        $basket->save();
    }

    /**
     * @param Events\BasketOrdered $event
     */
    public function whenBasketOrdered(Events\BasketOrdered $event)
    {
        $basket = Basket::find((string) $event->basketId);
        $basket->status = $event->status;
        $basket->save();
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            Events\BasketPickedUp::class,
            'App\EnhancedBasketProjector@whenBasketPickedUp'
        );
        $events->listen(
            Events\BasketCustomerSet::class,
            'App\EnhancedBasketProjector@whenBasketCustomerSet'
        );
        $events->listen(
            Events\ProductPut::class,
            'App\EnhancedBasketProjector@whenProductPut'
        );
        $events->listen(
            Events\BasketOrdered::class,
            'App\EnhancedBasketProjector@whenBasketOrdered'
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
