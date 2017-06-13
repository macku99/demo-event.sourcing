<?php

use App\Domain\Australia;
use App\Domain\BasketId;
use App\Domain\Customer;
use Illuminate\Database\Seeder;
use Money\Money;

class BasketsTableSeeder extends Seeder
{
    protected $products, $processor, $basketRepository;

    public function __construct()
    {
        $this->basketRepository = app(\App\Domain\BasketRepository::class);
        $this->processor = new \App\Domain\Processor();
        //var_dump($processor->process($basket));

        $this->products = \App\Product::all()->mapWithKeys(function ($product) {
            return [$product->slug => $product->toArray()];
        });
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tap(factory(App\User::class)->create(), function ($user) {
            $basket = $this->pickUpBasket($user);
            $basket->put($this->productA(2));
            $basket->put($this->productB(2));
            $basket->order();
            $this->basketRepository->commit();
        });

        tap(factory(App\User::class)->create(), function ($user) {
            $basket = $this->pickUpBasket($user);
            $basket->put($this->productC(5));
            $basket->order();
            $this->basketRepository->commit();
        });

        tap(factory(App\User::class)->create(), function ($user) {
            $basket = $this->pickUpBasket($user);
            $basket->put($this->productD(1));
            $basket->order();
            $this->basketRepository->commit();
        });

        tap(factory(App\User::class)->create(), function ($user) {
            $basket = $this->pickUpBasket($user);
            $basket->put($this->productE(10));
            $basket->order();
            $this->basketRepository->commit();
        });

        tap(factory(App\User::class)->create(), function ($user) {
            $basket = $this->pickUpBasket($user);
            $basket->put($this->productA(10));
            $basket->put($this->productB(5));
            $basket->put($this->productC(2));
            $this->basketRepository->commit();
        });
    }

    function pickUpBasket($user)
    {
        $basket = \App\Domain\Basket::create(BasketId::generate(), new Australia);
        $this->basketRepository->add($basket);

        $basket->pickedUpBy(new Customer($user->id, $user->name, $user->email));

        return $basket;
    }

    protected function productA($quantity)
    {
        $productA = new \App\Domain\Product(
            array_get($this->products, 'product-A.id'), array_get($this->products, 'product-A.name'),
            Money::AUD(array_get($this->products, 'product-A.price')), $quantity
        );
        $productA->isFreebie();

        return $productA;
    }

    protected function productB($quantity)
    {
        $productB = new \App\Domain\Product(
            array_get($this->products, 'product-B.id'), array_get($this->products, 'product-B.name'),
            Money::AUD(array_get($this->products, 'product-B.price')), $quantity
        );

        return $productB;
    }

    protected function productC($quantity)
    {
        $productC = new \App\Domain\Product(
            array_get($this->products, 'product-C.id'), array_get($this->products, 'product-C.name'),
            Money::AUD(array_get($this->products, 'product-C.price')), $quantity
        );
        $productC->isNonTaxable();

        return $productC;
    }

    protected function productD($quantity)
    {
        $productD = new \App\Domain\Product(
            array_get($this->products, 'product-D.id'), array_get($this->products, 'product-D.name'),
            Money::AUD(array_get($this->products, 'product-D.price')), $quantity
        );

        return $productD;
    }

    protected function productE($quantity)
    {
        $productE = new \App\Domain\Product(
            array_get($this->products, 'product-E.id'), array_get($this->products, 'product-E.name'),
            Money::AUD(array_get($this->products, 'product-E.price')), $quantity
        );

        return $productE;
    }
}
