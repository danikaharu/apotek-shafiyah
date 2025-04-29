@extends('layouts.user.index')

@section('content')
    <div class="container text-center">
        <h1 class="text-success">🎉 Pembayaran Berhasil!</h1>
        <p>Terima kasih, pesanan Anda telah kami terima.</p>

        <h5>Pilih cara pengambilan obat:</h5>

        <div>
            <form action="{{ route('order.selfPickup', ['order_id' => $order->id]) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary">Ambil Sendiri</button>
            </form>

            <form action="{{ route('order.maxim', ['order_id' => $order->id]) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">Pesan Maxim</button>
            </form>
        </div>
    </div>
@endsection
