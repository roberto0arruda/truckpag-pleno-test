<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);

        return Product::paginate($limit);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        return $product;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): \Illuminate\Http\Response
    {
        $product->status = 'trash';

        $product->save();

        return response()->noContent();
    }
}
