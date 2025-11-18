@component('mail::message')
# Objednávka č. {{ $order->increment_number }} od OOO Pizza

Dobrý den,<br>
pro naši pobočku **{{ $order->branch->name }}** objednáváme následující zboží:

@component('mail::table')
| SKU | Název | Množství |
|:--- |:----- | --------:|
@foreach($items as $item)
| {{ $item->product->sku }} | {{ $item->product->name }} | {{ $item->quantity }} ks |
@endforeach
@endcomponent

---

@if ($order->branch)
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top:16px;">
    <tr>
        <td valign="top" width="{{ $order->branch->manager ? '50%' : '100%' }}" style="padding-right:12px;">
            <h2 style="margin:0 0 8px;">Adresa doručení</h2>
            {{ config('app.frontend_name') }}<br>
            {{ $order->branch->street }}<br>
            {{ $order->branch->post_code }} {{ $order->branch->city }}<br>
        </td>
        @if ($order->branch->manager)
            <td valign="top" width="50%" style="padding-left:12px; border-left:1px solid #e2e2e2;">
                <h2 style="margin:0 0 8px;">Odpovědná osoba</h2>
                {{ $order->branch->manager->name }}<br>
                {{ $order->branch->manager->email }}<br>
                {{ $order->branch->manager->phone }}<br>
            </td>
        @endif
    </tr>
</table><br><br>
@endif

Děkujeme a přejeme pěkný den.<br>
{{ config('app.frontend_name') }}
@endcomponent
