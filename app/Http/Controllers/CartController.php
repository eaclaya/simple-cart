<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = collect();

        if ($request->user()) {
            $items = $request->user()
                ->cartItems()
                ->with('product')
                ->get()
                ->map(function (CartItem $item) {
                    $lineTotal = $item->quantity * $item->product->price;

                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'price' => $item->product->price,
                            'stock_quantity' => $item->product->stock_quantity,
                            'image_url' => $item->product->image_url,
                        ],
                        'line_total' => $lineTotal,
                    ];
                });
        }

        return Inertia::render('Cart/Index', [
            'items' => $items,
            'subtotal' => $items->sum('line_total'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $request->user()->cart()->firstOrCreate();
        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $newQuantity = $cartItem->exists ? $cartItem->quantity + $validated['quantity'] : $validated['quantity'];

        if ($newQuantity > $product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Requested quantity exceeds available stock.',
            ]);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return back()->with('success', "{$product->name} added to cart");
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart?->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = $cartItem->product;

        if ($validated['quantity'] > $product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Requested quantity exceeds available stock.',
            ]);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return back()->with('success', 'Cart updated');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart?->user_id !== $request->user()->id) {
            abort(403);
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return back()->with('success', "{$productName} removed from cart");
    }
}
