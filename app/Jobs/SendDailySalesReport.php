<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = Carbon::today();

        $items = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->whereDate('created_at', $date)
            ->groupBy('product_id')
            ->with('product:id,name')
            ->get()
            ->map(function (OrderItem $item) {
                return [
                    'product_name' => $item->product?->name ?? 'Unknown',
                    'total_quantity' => (int) $item->total_quantity,
                    'total_revenue' => (float) $item->total_revenue,
                ];
            });

        Mail::to(config('store.admin_email'))
            ->send(new DailySalesReport($date, $items));
    }
}
