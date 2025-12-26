<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock_quantity', 'image_url']);

        $cartItemQuantities = $request->user()
            ? $request->user()
                ->cartItems()
                ->pluck('quantity', 'product_id')
                ->toArray()
            : [];

        return Inertia::render('Products/Index', [
            'products' => $products,
            'cartItemQuantities' => $cartItemQuantities,
        ]);
    }

    public function apiIndex()
    {
        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock_quantity', 'image_url']);

        return response()->json($products);
    }

    public function show(Request $request, Product $product)
    {
        $cartItemQuantities = $request->user()
            ? $request->user()
                ->cartItems()
                ->pluck('quantity', 'product_id')
                ->toArray()
            : [];

        return Inertia::render('Products/Show', [
            'product' => $product->only(['id', 'name', 'price', 'stock_quantity', 'image_url']),
            'cartItemQuantities' => $cartItemQuantities,
        ]);
    }

    public function apiShow(Product $product)
    {
        return response()->json(
            $product->only(['id', 'name', 'price', 'stock_quantity', 'image_url'])
        );
    }
}
