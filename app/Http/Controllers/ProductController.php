<?php

namespace App\Http\Controllers;

use App\Http\Resources\productResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return new productResource($products, 'Success', 'Products retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|in:Import,Local',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return new productResource(null, 'Failed', $validator->errors());
        }

        $product = Product::create($request->all());
        return new productResource($product, 'Success', 'Product created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            return new productResource($product, 'Success', 'Product retrieved successfully');
        } else {
            return new productResource(null, 'Failed', 'Product not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($request->all());
            return new productResource($product, 'Success', 'Product updated successfully');
        } else {
            return new productResource(null, 'Failed', 'Product not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return new productResource(null, 'Success', 'Product deleted successfully');
        } else {
            return new productResource(null, 'Failed', 'Product not found');
        }
    }
}
