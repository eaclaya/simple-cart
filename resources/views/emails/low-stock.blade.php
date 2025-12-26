<x-mail::message>
# Low stock alert

The product **{{ $product->name }}** is running low.

- Current stock: {{ $product->stock_quantity }}
- Threshold: {{ $threshold }}

Please restock soon.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
