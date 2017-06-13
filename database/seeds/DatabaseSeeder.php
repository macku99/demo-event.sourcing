<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit('No seeders will be run in production!');
        }

        $this->call(ProductsTableSeeder::class);
        $this->call(BasketsTableSeeder::class);
    }
}
