<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Product::class)->create(['slug' => 'product-A', 'price' => 100, 'freebie' => true]);
        factory(App\Product::class)->create(['slug' => 'product-B', 'price' => 500]);
        factory(App\Product::class)->create(['slug' => 'product-C', 'price' => 1000, 'taxable' => false]);
        factory(App\Product::class)->create(['slug' => 'product-D', 'price' => 2500]);
        factory(App\Product::class)->create(['slug' => 'product-E', 'price' => 5000]);
    }
}
