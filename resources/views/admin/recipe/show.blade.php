@extends('layouts.admin.index')

@section('title', 'Detail Resep')

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h4>Detail Resep</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Detail Resep</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-info"></div>
        <div class="card-body">
            <table class="table table-bordered table-sm text-center">
                <tbody>
                    <tr>
                        <td rowspan="5" data-target="#modal-detail" data-toggle="modal" href="#">
                            <img src="{{ asset('storage/upload/resep/' . $recipe->image) }}" width="100">
                        </td>
                    </tr>
                    <tr>
                        <td>Pembeli</td>
                        <td>:</td>
                        <td>{{ $recipe->customer->full_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td><span class="badge bg-orange" style="font-size:16px">{{ $recipe->status() }}</span></td>
                    </tr>
                    <tr>
                        <td>Berakhir</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($recipe->created_at)->addHours(24)->translatedFormat('d F Y H:i') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            @if ($recipe->status == 1)
                <form action="{{ route('admin.approveRecipe', $recipe->id) }}" method="POST">
                    @csrf

                    <div id="product-list">
                        <div class="row mb-2 product-item">
                            <div class="col-md-5">
                                <select name="products[0][product_id]" class="form-control" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($allProducts as $product)
                                        @php
                                            $discount = $product->discount; // cek apakah produk ada diskon
                                            $finalPrice = $discount
                                                ? $product->price - $discount->discount_amount
                                                : $product->price;
                                        @endphp
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                            data-discount="{{ $discount ? $discount->discount_amount : 0 }}"
                                            data-final-price="{{ $finalPrice }}">
                                            {{ $product->name }}
                                            @if ($discount)
                                                <span class="text-danger">Diskon: @currency($discount->discount_amount)</span>
                                            @endif
                                            (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="products[0][quantity]" class="form-control" min="1"
                                    placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="products[0][price]" class="form-control" placeholder="Harga"
                                    required readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-row">&times;</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-row">+ Tambah Produk</button>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check-circle"></i>
                        Verifikasi & Tambahkan ke Keranjang
                    </button>
                </form>

                <form action="{{ route('admin.rejectRecipe', $recipe->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block"><i class="fa fa-times-circle"></i> Tolak
                        Resep</button>
                </form>
            @endif

            <!-- Modal detail gambar -->
            <div class="modal fade" id="modal-detail">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/upload/resep/' . $recipe->image) }}" style="width:100%;"
                                alt="Gambar Resep">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let index = 1;

        document.getElementById('add-row').addEventListener('click', function() {
            const list = document.getElementById('product-list');
            const item = document.createElement('div');
            item.className = 'row mb-2 product-item';

            item.innerHTML = `
            <div class="col-md-5">
                <select name="products[${index}][product_id]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($allProducts as $product)
                        @php
                            $discount = $product->discount;
                            $finalPrice = $discount ? $product->price - $discount->discount_amount : $product->price;
                        @endphp
                        <option value="{{ $product->id }}" 
                            data-price="{{ $product->price }}" 
                            data-discount="{{ $discount ? $discount->discount_amount : 0 }}" 
                            data-final-price="{{ $finalPrice }}">
                            {{ $product->name }} 
                            @if ($discount)
                                <span class="text-danger">Diskon: @extends('layouts.admin.index')</span>
                            @endif
                            (Stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[${index}][quantity]" class="form-control" min="1" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[${index}][price]" class="form-control" placeholder="Harga" required readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-row">&times;</button>
            </div>
        `;

            list.appendChild(item);
            index++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                e.target.closest('.product-item').remove();
            }
        });

        // Handle price update when product is selected
        document.addEventListener('change', function(e) {
            if (e.target.matches('select[name^="products"]')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const finalPrice = selectedOption.getAttribute('data-final-price');

                const row = e.target.closest('.product-item');
                const priceInput = row.querySelector('input[name^="products"][name$="[price]"]');
                if (priceInput && finalPrice) {
                    priceInput.value = finalPrice;
                }
            }
        });
    </script>
@endpush
