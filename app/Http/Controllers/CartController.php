<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCartItemRequest;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = collect();
        $subtotal = 0;

        if ($request->user()) {
            $cartItems = $request->user()
                ->cartItems()
                ->with('product')
                ->get();

            $items = CartItemResource::collection($cartItems);
            $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);
        }

        return Inertia::render('Cart/Index', [
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(StoreCartItemRequest $request)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);
        $cart = $request->user()->cart()->firstOrCreate();
        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $newQuantity = $cartItem->exists ? $cartItem->quantity + $validated['quantity'] : $validated['quantity'];

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return back()->with('success', "{$product->name} added to cart");
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        $validated = $request->validated();

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return back()->with('success', 'Cart updated');
    }

    public function destroy(DestroyCartItemRequest $request, CartItem $cartItem)
    {
        $productName = $cartItem->product->name;
        $cartItem->delete();

        return back()->with('success', "{$productName} removed from cart");
    }
}
