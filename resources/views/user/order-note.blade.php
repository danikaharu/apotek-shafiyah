<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APOTEK SHAFIYAH - INVOICE</title>
    <!-- Menggunakan Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Tema AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body>
    <div class="container">
        <div class="area" style="max-width: 60%; margin: auto;">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="text-left">
                        <img src="{{ asset('template/dist/img/logo apotek.png') }}" alt="Logo Apotek"
                            style="width: 20%;">
                    </div>
                    <div class="text-center" style="font-size:14px;">
                        <p>
                            <b>APOTEK SHAFIYAH</b><br>
                            <span>{{ $profile->address }}</span>
                        </p>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6" style="font-size: 12px;">
                            <b>Menyediakan:</b><br>
                            <ul style="list-style: none; padding-left: 10%;">
                                <li>- Berbagai obat-obatan</li>
                                <li>- Vitamin Herbal</li>
                                <li>- Alat Kesehatan</li>
                            </ul>
                        </div>
                        <div class="col-6" style="font-size: 12px;">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Tanggal</td>
                                    <td>:</td>
                                    <td>{{ $order->created_at->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Kepada Yth</td>
                                    <td>:</td>
                                    <td>{{ $order->customer->full_name ?? '................................................' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered mt-4 text-center" style="font-size: 14px;">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Banyaknya</th>
                                <th>Nama Barang</th>
                                <th style="width: 20%;">Harga</th>
                                <th style="width: 20%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->detail_order as $detail)
                                <tr>
                                    <td>{{ $detail->amount }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>@currency($detail->price) </td>
                                    <td>@currency($detail->total_price)</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                                <td style="height: 30px;"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><b>Jumlah Total</b></td>
                                <td>@currency($totalPrice)</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="row mt-5">
                        <div class="col-6 text-center" style="font-size: 12px;font-weight:bold;">
                            <p>Penerima</p>
                            <hr style="width: 50%; color: black; margin-top: 60px;">
                        </div>
                        <div class="col-6 text-center" style="font-size: 12px;">
                            <p><b>Hormat Kami</b></p>
                            <hr style="width: 50%; color: black; margin-top: 60px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
