@extends('layouts.user.index')

@section('title', 'Detail Produk')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0  text-success"> Detail Produk</h5>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
            <hr class="custom-hr">
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content blink-animation">
            <div class="content">
                <div class="container">
                    <div class="row mb-2">
                        <div class="card card-solid">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="col-12">
                                            <img src="{{ asset('storage/upload/produk/' . $product->image) }}"
                                                class="product-image" alt="Product Image">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <h3 class="my-3"><b>{{ $product->name }}</b>
                                        </h3>
                                        <h5><b>Deskripsi :</b></h5>
                                        <p>{!! $product->information !!} </p>
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h4>Kategori</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>{{ $product->category->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4>Jenis Obat</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>{{ $product->type->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4>Stok Obat</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>{{ $product->stock }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="bg-gray py-2 px-3 mt-4">
                                            <h2 class="mb-0">
                                                @if ($product->discount == null)
                                                    @currency($product->price)
                                                @else
                                                    @currency($product->price - $product->discount->discount_amount)
                                                @endif
                                            </h2>
                                            <h5 class="mt-0">
                                                <small style="font-style: italic;">Harga Jual : @currency($product->price),
                                                    Diskon : @if ($product->discount == null)
                                                        -
                                                    @else
                                                        @currency($product->discount->discount_amount)
                                                    @endif
                                                </small>
                                            </h5>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-auto">
                                                <a class="btn btn-secondary btn-lg my-2" href="{{ url()->previous() }}">
                                                    <i class="fas fa-arrow-left fa-md mr-2"></i>
                                                    Kembali
                                                </a>
                                            </div>
                                            @if (auth()->user())
                                                <div class="col-auto">
                                                    <button type="button"
                                                        class="btn btn-success btn-lg my-2 add-to-cart-btn"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="fas fa-shopping-cart"></i> Tambah
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $(document).ready(function() {
            $('.add-to-cart-btn').click(function() {
                var productId = $(this).data('product-id');

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update jumlah badge cart
                        $('#badge-cart-count').text(response.total_items);

                        // Update isi modal troli
                        $('#modal-troli .modal-body').html(response.cart_html);

                        // Update total harga cart
                        $('#cart-total-price').text(response.cart_total_price_formatted);


                        alert('Produk berhasil ditambahkan ke troli!');
                    },
                    error: function(xhr) {
                        alert('Gagal menambahkan ke troli.');
                    }
                });
            });
        });
    </script>
@endpush
