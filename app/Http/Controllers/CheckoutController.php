<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Jobs\SendLowStockNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        DB::transaction(function () use ($cartItems, $user) {
            $total = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = Product::whereKey($cartItem->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($cartItem->quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => "Not enough stock for {$product->name}.",
                    ]);
                }

                $lineTotal = $product->price * $cartItem->quantity;
                $total += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                ]);

                $product->decrement('stock_quantity', $cartItem->quantity);

                if ($product->stock_quantity <= config('store.low_stock_threshold')) {
                    SendLowStockNotification::dispatch($product->id);
                }
            }

            $order->update(['total' => $total]);

            if ($user->cart) {
                $user->cart->items()->delete();
            }
        });

        return redirect()->route('products.index')->with('success', 'Order placed successfully.');
    }
}
