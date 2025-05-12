@extends('layouts.cashier.index')

@section('title', 'Kasir')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Konsumen</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link text-secondary" id="custom-content-above-home-tab" data-toggle="pill"
                                    href="#custom-content-above-home" role="tab"
                                    aria-controls="custom-content-above-home" aria-selected="true">Pembeli</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" id="custom-content-above-messages-tab" data-toggle="pill"
                                    href="#custom-content-above-messages" role="tab"
                                    aria-controls="custom-content-above-messages" aria-selected="false">Walk
                                    In Customer</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-content-above-tabContent">
                            <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel"
                                aria-labelledby="custom-content-above-home-tab">
                                <div class="form-group mt-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-info">Data pembeli</span>
                                        </div>
                                        <select name="" id="customerSelect" class="select2 form-control">
                                            <option></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-content-above-messages" role="tabpanel"
                                aria-labelledby="custom-content-above-messages-tab">
                                <div class="form-group mt-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-info">Walk In Customer</span>
                                        </div>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <input type="hidden" id="selectedCustomer" name="customer_id">
                </div>

                <div class="card card-secondary">
                    <div class="card-body table-responsive p-0" style="height:200px;">
                        <!-- Tabel -->
                        <table class="table table-sm text-center table-bordered table-striped mt-2" id="tabel">
                            <thead class="bg-navy">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kuantiti</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>
                <!-- /.card -->

                <div class="card">
                    <table class="table-hovered m-2" style="width: 100%;" id="total">
                        <tr>
                            <td>
                                <h4>Total</h4>
                            </td>
                            <td>
                                <h4>:</h4>
                            </td>
                            <td>
                                <h4 class="text-success" style="font-weight: bold;" id="total">Rp.</h5>
                            </td>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-block btn-primary" id="buyOrder"> BAYAR</button>
                </div>


            </div>
            <!-- /.col (left) -->
            <div class="col-md-7">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Produk Lainnya</h3>
                    </div>
                    <div class="card-body table-responsive" style="height:520px;">
                        <div class="row mb-2">
                            <div class="col-8">
                                <a class="form-control" data-widget="navbar-search" href="#" role="button">
                                    <i class="fas fa-search"></i>
                                </a>
                                <div class="navbar-search-block">
                                    <form class="form-inline">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-navbar" type="search"
                                                placeholder="Search" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-navbar" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-4">
                                <select class="form-control select2" style="width: 100%;" name="category"
                                    data-placeholder="-- Kategori Obat --">
                                    @foreach ($categories as $category)
                                        <option></option>
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <!-- /.input group -->
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-lg-3 col-sm-12">
                                    <div class="card d-flex flex-column"
                                        style="box-shadow: 1px 1px 5px 2px rgb(186, 184, 184); text-align:center;">

                                        <img src="{{ asset('storage/upload/produk/' . $product->image) }}"
                                            class="card-img-top" style="width: auto; height:150px;">

                                        <div class="card-body flex-grow-1">
                                            <b>{{ $product->name }}</b>
                                            <br>
                                            <span class="text-success">@currency($product->price)</span>

                                            <div class="discount-info mt-2">
                                                @if ($product->discount)
                                                    @if ($product->discount->discount_amount > 0)
                                                        <!-- Diskon Nominal -->
                                                        <span class="text-danger" style="font-size: 14px;">
                                                            -@currency($product->discount->discount_amount)
                                                        </span>
                                                    @endif

                                                    @if ($product->discount->discount_percentage > 0)
                                                        <!-- Diskon Persentase -->
                                                        <span class="text-danger" style="font-size: 14px;">
                                                            -{{ $product->discount->discount_percentage }}%
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted" style="font-size: 14px;">No Discount</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-footer text-center">
                                            <button data-product-id="{{ $product->id }}" type="button"
                                                class="btn btn-success m-2 tambahBaris"><i
                                                    class="fas fa-shopping-cart"></i>Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
            <!-- /.col-md-7 -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "-- Pilih Pembeli --",
                allowClear: true
            })

            $('.nav-link').on('click', function() {
                if ($(this).attr('id') === 'custom-content-above-home-tab') {
                    var selectedCustomerId = $('#customerSelect').val();
                    $('#selectedCustomer').val(selectedCustomerId);
                } else {
                    $('#selectedCustomer').val('');
                }
            });

            $('#customerSelect').on('change', function() {
                var selectedCustomerId = $(this).val();
                $('#selectedCustomer').val(selectedCustomerId);
            });

            function updateTotalPrice() {
                var total = 0;
                // Iterasi setiap baris dalam tabel
                $('#tabel tbody tr').each(function() {
                    // Ambil jumlah dan harga dari setiap baris
                    var amount = parseInt($(this).find('.amount').text());
                    var price = parseFloat($(this).find('.price').text());
                    var discount = parseFloat($(this).find('.discount').text());
                    var totalPrice = parseFloat($(this).find('.total-price').text());

                    // Hitung total harga setelah diskon per produk
                    var grandTotal = (price * amount) - discount;

                    // Tambahkan ke total keseluruhan
                    total += grandTotal;

                });

                // Tampilkan total harga yang diperbarui
                $('h4#total').text('Rp. ' + total.toFixed(0)); // Format tanpa desimal
            }




            $(document).on('click', '.hapusBaris', function() {
                var row = $(this).closest('tr');

                var cartId = $(this).data('cart-id');
                var productId = row.data('product-id');

                $.ajax({
                    type: 'DELETE',
                    url: '{{ route('admin.cart.destroy', ['cart' => ':cart_id']) }}'.replace(
                        ':cart_id', cartId),
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        row.remove();
                        alert('Produk berhasil dihapus.');
                        updateTotalPrice();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Terjadi kesalahan saat menghapus produk.');
                    }
                });
            });

            $('.tambahBaris').click(function() {

                var productId = $(this).data('product-id');

                var selectedCustomer = $('#selectedCustomer').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.cart.store') }}',
                    data: {
                        product_id: productId,
                        customer_id: selectedCustomer,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        alert('Produk berhasil ditambahkan ke keranjang belanja!');

                        var detail = response.detail;
                        var cartId = detail.cart_id;

                        var productId = detail.product.id;
                        var existingRow = $('#tabel tbody tr[data-product-id="' + productId +
                            '"]');

                        if (existingRow.length > 0) {
                            // Jika produk sudah ada dalam tabel, perbarui jumlah dan total harga
                            var amountCell = existingRow.find('.amount');
                            var priceCell = existingRow.find('.price');
                            var discountCell = existingRow.find('.discount');
                            var totalPriceCell = existingRow.find('.total-price');

                            var amount = detail.amount; // Kuantiti produk
                            var price = detail.price; // Harga per item
                            var discount = detail.discount; // Diskon per item

                            // Hitung total harga setelah diskon
                            var totalDiscount = discount *
                                amount; // Total diskon untuk jumlah produk
                            var totalPrice = (price * amount) -
                                totalDiscount; // Total harga setelah diskon

                            // Perbarui teks pada tabel
                            amountCell.text(amount);
                            discountCell.text(
                                totalDiscount); // Menampilkan total diskon yang sesuai
                            totalPriceCell.text(
                                totalPrice); // Menampilkan total harga setelah diskon
                        } else {
                            // Jika produk belum ada dalam tabel, tambahkan baris baru
                            var newRow = "<tr data-product-id='" + productId + "'>" +
                                "<td>" + detail.product.name + "</td>" +
                                "<td class='amount'>" + detail.amount + "</td>" +
                                "<td class='price'>" + detail.price + "</td>" +
                                "<td class='discount'>" + (detail.discount * detail.amount) +
                                "</td>" + // Total diskon untuk jumlah produk
                                "<td class='total-price'>" + (detail.price - detail.discount) +
                                "</td>" +
                                // Total harga setelah diskon
                                "<td><button class='btn btn-danger btn-sm hapusBaris' data-cart-id='" +
                                cartId + "'>Hapus</button></td>" +
                                "</tr>";

                            $('#tabel tbody').append(newRow);
                        }

                        updateTotalPrice();
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menambahkan produk ke keranjang belanja.';
                        alert(errorMessage);
                    }
                });
            });

            $('#buyOrder').off('click').on('click', function() {
                var rows = $('#tabel tbody tr');
                var selectedCustomer = $('#selectedCustomer').val();
                var total_price = $('h4#total').text().replace('Rp. ', '');
                var products = []; // Array untuk menyimpan detail produk

                // Loop melalui setiap baris di tabel untuk mendapatkan data produk
                rows.each(function() {
                    var productId = $(this).data('product-id');
                    var amountValue = $(this).find('.amount').text();
                    var priceValue = $(this).find('.price').text();
                    var discountValue = $(this).find('.discount').text();
                    var totalValue = $(this).find('.total-price').text();

                    // Tambahkan data produk ke array
                    products.push({
                        product_id: productId,
                        amount: amountValue,
                        price: priceValue,
                        discount: discountValue,
                        total_price: totalValue
                    });
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.order.store') }}',
                    data: {
                        customer_id: selectedCustomer,
                        total_price: total_price,
                        products: products,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Produk berhasil dibayar!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat membayar.';
                        alert(errorMessage);
                    }
                });
            });

        })
    </script>
@endpush
