<x-mail::message>
# Daily sales report ({{ $date->toDateString() }})

@if ($items->isEmpty())
No products were sold today.
@else
| Product | Quantity Sold | Revenue |
| --- | ---: | ---: |
@foreach ($items as $item)
| {{ $item['product_name'] }} | {{ $item['total_quantity'] }} | ${{ number_format($item['total_revenue'], 2) }} |
@endforeach
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
