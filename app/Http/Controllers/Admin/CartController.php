<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        $carts = Cart::query()
            ->with(['user', 'items.product'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (Cart $cart) {
                $items = $cart->items->map(function (CartItem $item) {
                    $lineTotal = $item->product
                        ? $item->product->price * $item->quantity
                        : 0;

                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'product' => [
                            'id' => $item->product?->id,
                            'name' => $item->product?->name,
                            'price' => $item->product?->price,
                            'image_url' => $item->product?->image_url,
                        ],
                        'line_total' => $lineTotal,
                        'added_at' => $item->created_at?->toDateTimeString(),
                    ];
                });

                return [
                    'id' => $cart->id,
                    'user' => [
                        'id' => $cart->user?->id,
                        'name' => $cart->user?->name,
                        'email' => $cart->user?->email,
                    ],
                    'items' => $items,
                    'items_count' => $items->count(),
                    'total' => $items->sum('line_total'),
                    'updated_at' => $cart->updated_at?->toDateTimeString(),
                ];
            });

        return Inertia::render('Dashboard/Carts', [
            'carts' => $carts,
            'total' => $carts->sum('total'),
        ]);
    }
}
