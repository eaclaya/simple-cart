<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::get('api/products', [ProductController::class, 'apiIndex'])->name('api.products.index');
Route::get('api/products/{product}', [ProductController::class, 'apiShow'])->name('api.products.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard/products', function () {
        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock_quantity', 'image_url']);

        return Inertia::render('Dashboard/Products', [
            'products' => $products,
        ]);
    })->name('dashboard.products');

    Route::get('dashboard/carts', function () {
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
    })->name('dashboard.carts');

    Route::get('dashboard/orders', function () {
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
    })->name('dashboard.orders');

    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

require __DIR__.'/settings.php';
