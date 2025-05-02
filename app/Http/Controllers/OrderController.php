<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\orderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return new orderResource($orders, 'success', 'Orders retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return new orderResource(null, 'Failed', $validator->errors());
        }

        //get user data
        $userResponse = Http::get("http://127.0.0.1:8001/api/buyers/{$request->user_id}");
        if (!$userResponse->ok() || !$userResponse['data']) {
            return new orderResource(null, 'Failed', 'User not found');
        }
        $user = $userResponse['data'];

        //get product data
        $productResponse = Http::get("http://127.0.0.1:8002/api/products/{$request->product_id}");
        if (!$productResponse->ok() || !$productResponse['data']) {
            return new orderResource(null, 'Failed', 'Product not found');
        }
        $product = $productResponse['data'];

        $total_price = $product['price'] * $request->quantity;
        
        $order = Order::create([
            'user_id'      => $request->user_id,
            'product_id'   => $request->product_id,
            'name'         => $user['name'],
            'email'        => $user['email'],
            'product_name' => $product['name'],
            'description'  => $product['description'],
            'quantity'     => $request->quantity,
            'total_price'  => $total_price,
            'order_date'   => $request->order_date,
            'status'       => 'pending',
        ]);

        return new orderResource($order, 'Success', 'Order created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            return new orderResource($order, 'Success', 'Order retrieved successfully');
        } else {
            return new orderResource(null, 'Failed', 'Order not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order){
            $order->update($request->all());
            return new orderResource($order, 'Success', 'Order updated successfully');
        } else {
            return new orderResource(null, 'Failed', 'Order not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return new orderResource(null, 'Success', 'Order deleted successfully');
        } else {
            return new orderResource(null, 'Failed', 'Order not found');
        }
    }
}
