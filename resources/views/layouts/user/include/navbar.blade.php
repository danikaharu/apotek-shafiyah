<!-- Navbar Utama -->
<nav class="main-header navbar navbar-expand-md navbar-light bg-success">
    <div class="container">
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('template/dist/img/ular.png') }}" alt="Logo" class="brand-image img-circle"
                style="opacity: .8; width: 30px; height: 30px;">
            <span class="brand-text font-weight-light text-white ml-2">{{ Str::upper(config('app.name')) }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search Bar -->
            <form action="{{ route('search.product') }}" method="GET" class="form-inline mx-auto">
                <input class="form-control" type="search" name="search" placeholder="Cari Obat" style="width: 400px;">
                <button class="btn btn-info ml-2" type="submit"><i class="fa fa-search"></i></button>
            </form>

            <!-- User Menu -->
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link text-white" href="{{ route('account.index') }}">Profil Saya</a>
                        @if (auth()->user()->customer && auth()->user()->customer->image)
                            <img src="{{ asset('storage/upload/avatar/' . auth()->user()->customer->image) }}"
                                class="img img-circle ml-2" style="width:30px;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->username }}"
                                class="img img-circle ml-2" style="width:30px;">
                        @endif
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-info btn-sm" href="{{ route('login') }}">
                            <i class="fas fa-user"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Navbar Kategori -->
<nav class="main-header navbar navbar-expand-md navbar-light bg-white">
    <div class="container">
        <div class="btn-group mr-3">
            <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Kategori Obat
            </button>
            <div class="dropdown-menu">
                @foreach ($categories as $category)
                    <a href="{{ route('category', $category->id) }}" class="dropdown-item">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>

        @auth
            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-resep">
                <i class="fa fa-upload"></i> Upload Resep
            </a>

        @endauth

        <ul class="navbar-nav ml-auto">
            <li class="nav-item mr-2">
                <a href="#" data-toggle="modal" data-target="#modal-troli"
                    class="btn btn-outline-success position-relative" id="btn-troli">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        id="badge-cart-count">
                        {{ !empty($cart) && $cart->details ? $cart->details->sum('amount') : 0 }}
                    </span>
                </a>
            </li>

            @auth
                <li class="nav-item mr-2">
                    <a class="btn btn-outline-info btn-sm" href="{{ route('order.history') }}">
                        <i class="fas fa-history"></i> Riwayat Pembelian
                    </a>
                </li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa fa-power-off"></i> Logout
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>



@include('layouts.user.include.modal-troli')
@include('layouts.user.include.modal-resep')

@push('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script>
        $(document).ready(function() {
            $('#checkout-btn').click(function(e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Membuat Pesanan...');

                var products = [];
                var valid = true;
                var total_price = 0;

                // Loop untuk mengambil data produk dari tabel
                $('#tabel tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.find('.product_id').val();

                    // Validasi apakah product_id tersedia
                    if (!productId) {
                        valid = false;
                        return false; // keluar dari loop
                    }

                    var price = parseInt(row.find('.price').text().replace('Rp. ', '').replace(
                        /\./g, '').trim());
                    var discount = parseInt(row.find('.discount').text().trim()) ||
                        0; // ambil diskon
                    var total = price * (1 - discount / 100); // hitung harga setelah diskon

                    products.push({
                        product_id: productId,
                        amount: row.find('.amount').text().trim(),
                        price: price,
                        discount: discount,
                        total: total // total sudah terpotong diskon
                    });

                    total_price += total;
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
                                'Terjadi kesalahan saat memulai pembayaran. Silakan coba lagi.'
                            );
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
