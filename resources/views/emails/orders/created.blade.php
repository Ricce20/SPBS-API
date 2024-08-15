<x-mail::message>
# Order Confirmation

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Thank you for your purchase! Here are the details of your order:
- **Order ID:** {{ $order->id }}
- **Order Time:** {{ $order->time }}
- **Phone:** {{ $order->phone }}
- **Address:** {{ $order->address }}
- **Status:** {{ $order->status }}
</x-mail::message>

Thanks,<br>
{{ config('app.name') }}