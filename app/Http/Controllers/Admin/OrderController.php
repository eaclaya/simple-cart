<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $orders = Order::query()
            ->with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Order $order) {
                $items = $order->items->map(function ($item) {
                    $lineTotal = $item->price * $item->quantity;

                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'line_total' => $lineTotal,
                        'product' => [
                            'id' => $item->product?->id,
                            'name' => $item->product?->name,
                            'image_url' => $item->product?->image_url,
                        ],
                    ];
                });

                return [
                    'id' => $order->id,
                    'user' => [
                        'id' => $order->user?->id,
                        'name' => $order->user?->name,
                        'email' => $order->user?->email,
                    ],
                    'items' => $items,
                    'items_count' => $items->count(),
                    'total' => $order->total,
                    'created_at' => $order->created_at?->toDateTimeString(),
                ];
            });

        return Inertia::render('Dashboard/Orders', [
            'orders' => $orders,
            'total' => $orders->sum('total'),
        ]);
    }
}
