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

}
