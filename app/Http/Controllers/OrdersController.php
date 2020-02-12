<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrdersController extends Controller
{
    public function __construct()
    {
        \Auth::shouldUse('api');

        $this->authorizeResource(Order::class);
    }

    /**
     * @return OrderCollection
     */
    public function index()
    {
        return new OrderCollection(Order::where('user_id', \Auth::id())->get());
    }

    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateOrderRequest $request)
    {
        $order = new Order();
        $order->fill($request->validated());
        if (\Auth::check()) {
            $order->user_id = \Auth::id();
        }
        $order->save();

        if(is_array($request->products)){
            foreach($request->products as $product){
                $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            }
        }

        $order->calculateTotal();
        $order->save();

        return response(
            OrderResource::make($order)
        );
    }

    protected function resourceAbilityMap() : array
    {
        return array_merge(parent::resourceAbilityMap(), [
            'index' => 'index',
        ]);
    }
}

