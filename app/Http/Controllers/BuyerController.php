<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Buyer;
use App\Http\Resources\buyerResource;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buyers = Buyer::all();
        return new buyerResource($buyers, 'Success', 'Buyers data retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:buyers,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return new buyerResource(null, 'Failed', $validator->errors());
        }

        $buyer = Buyer::create($request->all());
            return new buyerResource($buyer, 'Success', 'Buyer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buyer = Buyer::find($id);
        if ($buyer) {
            return new buyerResource($buyer, 'Success', 'Buyer data retrieved successfully');
        } else {
        return new buyerResource(null, 'Failed', 'Buyer not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buyer = Buyer::find($id);
        if ($buyer) {
            $buyer->update($request->all());
            return new buyerResource($buyer, 'Success', 'Buyer updated successfully');
        } else {
            return new buyerResource(null, 'Failed', 'Buyer not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buyer = Buyer::find($id);
        if ($buyer) {
            $buyer->delete();
            return new buyerResource(null, 'Success', 'Buyer deleted successfully');
        } else {
            return new buyerResource(null, 'Failed', 'Buyer not found');
        }
    }
}
