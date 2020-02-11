<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $pizzas = [
            [
                'name' => '4 Cheeses',
                'image' => '4cheeses.jpeg'
            ],
            [
                'name' => 'Chorizo',
                'image' => 'chorizo.jpeg'
            ],
            [
                'name' => 'Pepperoni',
                'image' => 'pepperoni.jpeg'
            ],
            [
                'name' => 'Cheeseburger',
                'image' => 'cheeseburger.jpg'
            ],
            [
                'name' => 'Fresh',
                'image' => 'fresh.jpeg'
            ],
            [
                'name' => 'Cheese And Ham',
                'image' => 'cheese-ham.jpg'
            ],
            [
                'name' => 'Chicken',
                'image' => 'chicken.jpeg'
            ],
            [
                'name' => 'Cheese',
                'image' => 'cheese.jpeg'
            ],
            [
                'name' => 'Heart Pepperoni',
                'image' => 'heart.jpeg'
            ],
        ];

        for($i = 0; $i < count($pizzas); $i++){
            factory(\App\Models\Product::class)->create($pizzas[$i]);
        }
    }
}
