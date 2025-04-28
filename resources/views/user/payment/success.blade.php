@extends('layouts.user.index')

@section('content')
    <div class="container text-center">
        <h1 class="text-success">🎉 Pembayaran Berhasil!</h1>
        <p>Terima kasih, pesanan Anda telah kami terima.</p>

        <h5>Pilih cara pengambilan obat:</h5>

        <div>
            <a href="{{ route('order.selfPickup', ['order_id' => $order->id]) }}" class="btn btn-primary">Ambil Sendiri</a>
            <a href="{{ route('order.maxim', ['order_id' => $order->id]) }}" class="btn btn-warning">Pesan Maxim</a>
        </div>
    </div>
@endsection
