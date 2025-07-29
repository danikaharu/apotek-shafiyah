@extends('layouts.admin.index')

@section('title', 'Detail Order')

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h4>Detail Order</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Detail Order</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="bg-navy">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>QTY</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->detail_order as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>@currency($detail->price)</td>
                                <td>{{ $detail->amount }}</td>
                                <td>@currency($detail->total_price)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h6>Detail Customer</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($order->user && $order->user->customer && $order->user->customer->image != null)
                                            <img src="{{ asset('storage/upload/user/' . $order->user->customer->image) }}"
                                                alt="User" style="max-width:40px;">
                                        @else
                                            <img src="{{ asset('template/dist/img/avatar4.png') }}" style="max-width:40px;">
                                        @endif
                                    </td>
                                    <td></td>
                                    <td>{{ $order->user->customer->full_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <td>:</td>
                                    <td>{{ $order->user->customer->phone_number ?? '' }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <table class="table  table-sm">
                    <tbody>
                        <tr>
                            <td>Sub Total</td>
                            <td>:</td>
                            <td>@currency($totalPrice)</td>
                        </tr>
                        <tr>
                            <td>Diskon</td>
                            <td>:</td>
                            <td> @currency($discount)</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>:</td>
                            <td>@currency($order->total_price - $discount)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-bold">Alamat Customer :</h6>
                        <p>{{ $order->user->customer->address ?? '' }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
