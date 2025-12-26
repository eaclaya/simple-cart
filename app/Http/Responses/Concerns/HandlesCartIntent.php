<?php

namespace App\Http\Responses\Concerns;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

trait HandlesCartIntent
{
    protected function handleCartIntent(Request $request): void
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        if (! $productId || $quantity < 1 || ! $request->user()) {
            return;
        }

        $product = Product::find($productId);

        if (! $product || $product->stock_quantity < 1) {
            return;
        }

        $cart = $request->user()->cart()->firstOrCreate();
        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $newQuantity = $cartItem->exists
            ? $cartItem->quantity + $quantity
            : $quantity;

        $newQuantity = min($newQuantity, $product->stock_quantity);

        if ($newQuantity < 1) {
            return;
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        session()->flash('success', "{$product->name} added to cart");
    }

    protected function sanitizeRedirect(?string $redirect): ?string
    {
        if (! $redirect) {
            return null;
        }

        $parsed = parse_url($redirect);

        if ($parsed === false) {
            return null;
        }

        if (isset($parsed['scheme']) || isset($parsed['host'])) {
            return null;
        }

        if (! str_starts_with($redirect, '/')) {
            return null;
        }

        return $redirect;
    }
}
