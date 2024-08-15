<x-mail::message>
# Orden Enviada a su domicilio

## Detalles de la orden:

- **Order ID:** {{ $order->id }}
- **Order Time:** {{ $order->time }}
- **Phone:** {{ $order->phone }}
- **Address:** {{ $order->address }}
- **Status:** {{ $order->status }}

## Envío

- **Empresa de Envíos:** {{ $shippingCompany }}
- **Número de Guía:** {{ $trackingNumber }}

## Productos
@foreach ($detailsOrder as $item)
- **Producto:** {{ $item->product->name }}
- **Cantidad:** {{ $item->quantity }}
- **Subtotal:** {{ $item->subtotal }}
@endforeach

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
