@extends('layouts.user.index')

@section('title', 'Riwayat Pembelian')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0  text-success">Riwayat Pembelian <small class="text-muted"></small></h5>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
            <hr class="custom-hr">
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content blink-animation">
            <div class="content">
                <div class="container">

                    <!-- card  -->
                    @foreach ($orders as $order)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Order #{{ $order->id }} - Tanggal: {{ $order->created_at->format('d M Y') }}</h5>
                            </div>
                            @foreach ($order->detail_order as $detail)
                                <div class="container p-3 d-flex align-items-center">
                                    <img src="{{ asset('storage/upload/produk/' . $detail->product->image) }}"
                                        alt="Gambar" style="max-width: 100px; margin-right: 15px;">
                                    <div style="flex-grow: 1;">
                                        <h5>{{ $detail->product->name }}</h5>
                                        <small class="text-success"><b>{{ $detail->product->category->name }} -
                                                {{ $detail->product->type->name }}</b></small><br>
                                        <small class="text-secondary"><b>Jumlah: {{ $detail->amount }} Pcs</b></small>
                                    </div>
                                    <div style="text-align: right;">
                                        <h6 style="color: rgb(28, 26, 26); padding: 5px; margin-top: 10px;">
                                            Harga: @currency($detail->price) / Pcs</h6>
                                    </div>
                                </div>
                            @endforeach
                            <div class="card-footer text-right">
                                <h5 style="color: rgb(16, 132, 182); padding: 5px; margin-top: 10px;">
                                    Total: @currency($order->total_price)</h5>
                                <a href="{{ route('order.note', $order->id) }}" class="btn btn-success btn-sm"
                                    target="_blank">Download Struk</a>
                            </div>
                        </div>
                    @endforeach

                    {{-- end card --}}

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
