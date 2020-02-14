<?php

namespace Tests\Feature;

use App\Models\Order;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TopDigital\Auth\Models\User;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    private $products;
    private $credentials;
    private $userId;

    public function setUp() : void
    {
        parent::setUp();

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
            $product = factory(\App\Models\Product::class)->create($pizzas[$i]);
            $this->products[] = $product->id;
        }

        $faker = Factory::create();
        $this->credentials = [
            'email' => $faker->email,
            'password' => $faker->word(),
        ];

        factory(\TopDigital\Auth\Models\User::class)->create($this->credentials);

        $this->userId = User::all()->first()->id;
    }

    public function testUnauthorizedSendOrder()
    {
        // check products
        $response = $this->getJson('/api/products/');

        $response
            ->assertStatus(200)
            ->assertJsonCount( 9,'data')
        ;

        // send order
        $data = [
            'address' => 'some address',
            'contact' => 'some contact',
            'delivery_cost' => '1.11',
            'products' => [
                [
                    'id' => array_rand($this->products),
                    'quantity' => rand(1,100),
                ]
            ]
        ];
        $response = $this->postJson('/api/orders/', $data );

        $orders = Order::with('products')->get();
        $this->assertCount(1, $orders);

        /**
         * @var Order $order
         */
        $order = $orders->first();
        $this->assertCount(1, $order->products()->get());

        $response
            ->assertStatus(200)
            ->assertJsonPath('id', $order->id)
            ->assertJsonPath('user_id', 0)
            ->assertJsonPath('address', $data['address'])
            ->assertJsonPath('contact', $data['contact'])
            ->assertJsonPath('delivery_cost', $data['delivery_cost'])
        ;

        $product = $order->products()->first();
        $this->assertEquals($data['products'][0]['id'], $product->id);
        $this->assertEquals($data['products'][0]['quantity'], $product->pivot->quantity);

        // check orders
        $response = $this->getJson('/api/orders/');

        $response
            ->assertStatus(401)
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function testAuthorizedSendOrder()
    {
        // set oauth keys
        $this->artisan('auth:secret')->assertExitCode(0);
        $this->artisan('passport:install')->assertExitCode(0);

        $client = \DB::table('oauth_clients')->where('name', 'CMS')->first();
        $oauthParams = [
            'client_id' => $client->id,
            'client_secret' => $client->secret
        ];

        // authorize
        $response = $this->postJson('/api/login', $oauthParams + $this->credentials);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token'])
        ;

        // append auth header to requests
        $headers = [
            'Authorization' => 'Bearer '. $response->decodeResponseJson('access_token')
        ];

        // check products
        $response = $this->getJson('/api/products/', $headers);

        $response
            ->assertStatus(200)
            ->assertJsonCount( 9,'data')
        ;

        // send order
        $data = [
            'address' => 'some address',
            'contact' => 'some contact',
            'delivery_cost' => '1.11',
            'products' => [
                [
                    'id' => array_rand($this->products),
                    'quantity' => rand(1,100),
                ]
            ]
        ];
        $response = $this->postJson('/api/orders/', $oauthParams + $data, $headers );

        $orders = Order::with('products')->get();
        $this->assertCount(1, $orders);

        /**
         * @var Order $order
         */
        $order = $orders->first();
        $this->assertCount(1, $order->products()->get());

        $response
            ->assertStatus(200)
            ->assertJsonPath('id', $order->id)
            ->assertJsonPath('user_id', $this->userId)
            ->assertJsonPath('address', $data['address'])
            ->assertJsonPath('contact', $data['contact'])
            ->assertJsonPath('delivery_cost', $data['delivery_cost'])
        ;

        $product = $order->products()->first();
        $this->assertEquals($data['products'][0]['id'], $product->id);
        $this->assertEquals($data['products'][0]['quantity'], $product->pivot->quantity);

        // check orders
        $response = $this->getJson('/api/orders/', $headers);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        $userOrder = $response->decodeResponseJson('data')[0];
        $this->assertEquals($userOrder, $order->withoutRelations()->toArray());
    }
}
