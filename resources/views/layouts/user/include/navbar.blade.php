<nav class="main-header navbar navbar-expand-md navbar-light navbar-green">
    <div class="container">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <img src="{{ asset('template/dist/img/logo ular.png') }}" alt="Logo" class="brand-image img-circle"
                style="opacity: .8">
            <span class="brand-text font-weight-light text-white">{{ Str::upper(config('app.name')) }}</span>
        </a>

        <!-- Tombol toggle untuk tampilan SM dan MD -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Daftar menu navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="mx-auto order-0">
                <form action="{{ route('search.product') }}" class="form-inline" method="GET">
                    <input class="form-control" type="search" placeholder="Cari Obat" style="width: 500px;"
                        name="search">
                    <button class="btn btn-info my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <!-- Menu kanan navbar -->
            <ul class="order-1 order-md-3 navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                @if (auth()->user())
                    <li class="nav-item dropdown">
                        <a class="text-white" href="{{ route('account.index') }}">
                            Profil Saya
                        </a>
                        @if (auth()->user() && auth()->user()->customer && auth()->user()->customer->image == null)
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->username }}"
                                class="img img-circle" style="width:30px;">
                        @else
                            <img src="{{ asset('storage/upload/avatar/' . auth()->user()->customer->image) }}"
                                class="img img-circle" style="width:30px;">
                        @endif

                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="btn btn-info btn-sm" href="{{ route('login') }}">
                            <i class="fas fa-user"></i>
                            &nbsp Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<nav class="main-header navbar navbar-expand-md navbar-light navbar-white navbar-fixed">
    <div class="container">
        <div class="btn-group mr-5">
            <a type="button" class="btn btn-outline-success  dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Kategori obat
            </a>
            <div class="dropdown-menu dropdown-menu-left">
                @foreach ($categories as $category)
                    <a href="{{ route('category', $category->id) }}" class="dropdown-item">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>

        <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-resep"><i
                class="fa fa-upload"></i> Upload resep</a>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <!-- Messages Dropdown Menu -->
            <li class="nav-item mr-2">
                <a class="btn btn-sm btn-success" href="#" data-toggle="modal" data-target="#modal-troli">
                    <i class="fas fa-shopping-cart"></i>
                    Lihat troli
                </a>
                @if (auth()->user())
            <li class="nav-item">
                <a class="btn btn-sm btn-outline-info" href="{{ route('order.history') }}">
                    <i class="fas fa-history"></i>
                    Riwayat pembelian
                </a>
            </li>
            <li class="nav-item">
                <div class="btn-group ml-2">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        class="btn btn-sm btn-outline-danger" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-power-off"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
            @endif
        </ul>
    </div>
</nav>


<!-- modal troli -->
<div class="modal fade" id="modal-troli" data-backdrop="static">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title">Troli Belanja</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if (auth()->user())
                <div class="modal-body">
                    <div class="row">
                        {{-- <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light"></div>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Unggah Resep</td>
                                            <td>:</td>
                                            <td>
                                                <h5 class="text-success">Berhasil</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            <td>
                                                <h5 class="text-orange">Ditnjau</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Berakhir</td>
                                            <td>:</td>
                                            <td>23 : 59 WITA</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                        @if ($orders)
                            <div class="col-md-8">
                                <div class="card">
                                    <table class="table table-bordered table-sm font-sm">
                                        <thead class="bg-navy">
                                            <tr>
                                                <th>Id Order</th>
                                                <th>Tanggal</th>
                                                <th>Item Dipesan</th>
                                                <th>Total</th>
                                                <th>Status Bayar</th>
                                                <th>Cancel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>{{ $order->detail_order->count() }} item</td>
                                                    <td>Rp. {{ $order->total_price }}</td>
                                                    <td>{{ $order->status() }}</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('cancel.order', $order->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-warning bg-orange"><i
                                                                    class="fa fa-window-close text-white"></i></button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>

                    <table id="tabel" class="table table-sm table-striped">
                        <thead class="bg-info">
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Kuanti</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Bayar</th>
                            <th>Hapus</th>
                        </thead>
                        <tbody>
                            @foreach ($carts as $cart)
                                @foreach ($cart->details as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <input type="hidden" class="product_id" value="{{ $detail->product->id }}">
                                        <td><a href="#"
                                                class="text-success">{{ $detail->product->name }}</a><br>
                                            @if ($detail->product->type_id == 1)
                                                <small class="text-danger">Harus Dengan
                                                    resep dokter</small>
                                            @endif
                                        </td>
                                        <td class="amount">{{ $detail->amount }} </td>
                                        <td class="price">Rp. {{ $detail->total_price }}</td>
                                        <td class="discount">{{ $detail->discount }}</td>
                                        <td class="total">Rp. {{ $detail->total_price }}</td>
                                        <td>
                                            <form
                                                action="{{ route('destroy.cart', [$detail->cart_id, $detail->product->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fa fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="bg-success py-2 px-3 mt-4">
                        <h2 class="mb-0" id="totalPrice">
                            Total Harga : Rp. {{ $cart->total_price ?? 0 }}
                        </h2>
                        <h5 class="mt-0">
                            <small style="font-style: italic;">*Harga belum termasuk ongkir</small>
                        </h5>
                    </div>
                    <button id="buatOrder" type="submit" class="btn btn-info btn-sm btn-block"><i
                            class="fas fa-check" aria-hidden="true" style="font-size: 14px;"></i>
                        Buat Pesanan</button>
                </div>
            @else
                <div class="modal-body">
                    Login Dulu
                </div>
            @endif
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal troli -->

<!-- modal resep -->
<div class="modal fade" id="modal-resep">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title">Upload Resep</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if (auth()->user())
                <div class="modal-body">
                    <form action="{{ route('store.recipe') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputFile">Upload gambar</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input"
                                        id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Pilih
                                        Gambar</label>
                                </div>
                            </div>
                        </div>
                        <hr class="custom-hr">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-upload"
                            aria-hidden="true" style="font-size: 12px;"></i>
                        Kirim Gambar</button>
                    </form>
                </div>
            @else
                <div class="modal-body">
                    Login Dulu
                </div>
            @endif

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal resep -->

@push('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script>
        $(document).ready(function() {
            $('#buatOrder').click(function(e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Membuat Pesanan...');

                var products = [];
                var valid = true;

                // Loop untuk mengambil data produk dari tabel
                $('#tabel tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.find('.product_id').val();

                    // Validasi apakah product_id tersedia
                    if (!productId) {
                        valid = false;
                        return false; // keluar dari loop
                    }

                    // Push produk ke array
                    products.push({
                        product_id: productId,
                        amount: row.find('.amount').text().trim(),
                        price: row.find('.price').text().replace('Rp. ', '').replace(/\./g,
                            '').trim(),
                        discount: row.find('.discount').text().trim(),
                        total: row.find('.total').text().replace('Rp. ', '').replace(/\./g,
                            '').trim(),
                    });
                });

                // Validasi jika ada data produk yang tidak lengkap
                if (!valid) {
                    alert('Data produk tidak lengkap!');
                    $btn.prop('disabled', false).html('Buat Pesanan');
                    return;
                }

                // Ambil total harga dan formatkan
                var total_price = $('#totalPrice').text().replace('Total Harga : Rp. ', '').replace(/\./g,
                    '').trim();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('store.order') }}',
                    data: {
                        products: products,
                        total_price: total_price,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Pastikan menerima snap_token dan order_id dari response
                        if (response.snap_token && response.order_id) {
                            snap.pay(response.snap_token, {
                                onSuccess: function(result) {
                                    console.log(result);
                                    // Redirect ke halaman success dengan order_id
                                    window.location.href = '/payment/success/' +
                                        response.order_id;
                                },
                                onPending: function(result) {
                                    console.log(result);
                                    // Redirect ke halaman pending saat pembayaran dalam status pending
                                    window.location.href = '/payment/pending';
                                },
                                onError: function(result) {
                                    console.error(result);
                                    alert('Pembayaran gagal! Silakan coba lagi.');
                                    $btn.prop('disabled', false).html(
                                        'Buat Pesanan');
                                },
                                onClose: function() {
                                    alert('Pembayaran dibatalkan.');
                                    $btn.prop('disabled', false).html(
                                        'Buat Pesanan');
                                }
                            });
                        } else {
                            console.error('Snap Token atau Order ID tidak diterima.');
                            alert(
                                'Terjadi kesalahan saat memulai pembayaran. Silakan coba lagi.');
                            $btn.prop('disabled', false).html('Buat Pesanan');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan pada server.');
                        $btn.prop('disabled', false).html('Buat Pesanan');
                    }
                });
            });
        });
    </script>
@endpush
